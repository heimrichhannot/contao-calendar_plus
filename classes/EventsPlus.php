<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package ${CARET}
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


abstract class EventsPlus extends \Events
{

	public function getFilter($objModule)
	{
		\Controller::loadDataContainer('tl_calendar_events');

		$arrFilter = array();

		$arrFields = deserialize($objModule->formHybridEditable, true);

		// Return if there are no fields
		if (!is_array($arrFields) || empty($arrFields)) {
			return $arrFilter;
		}

		foreach($arrFields as $strKey)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_calendar_events']['fields'][$strKey];

			if(!is_array($arrData) || empty($arrData)) continue;

			$arrFilter[$strKey] = EventFilterHelper::getValueByDca(\Input::get($strKey), $arrData);
		}

		return $arrFilter;
	}

	/**
	 * Get all events of a certain period
	 * @param array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	protected function getAllEvents($arrCalendars, $intStart, $intEnd, $arrFilter=array())
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;
			$objCalendar = \CalendarModel::findByPk($id);

			// Get the current "jumpTo" page
			if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null)
			{
				$strUrl = $this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/events/%s'));
			}

			// Get the events of the current period
			$objEvents = CalendarPlusEventsModel::findCurrentByPidAndFilter($id, $intStart, $intEnd, $arrFilter);

			if ($objEvents === null)
			{
				continue;
			}

			while ($objEvents->next())
			{
				$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);

				// Recurring events
				if ($objEvents->recurring)
				{
					$arrRepeat = deserialize($objEvents->repeatEach);

					if ($arrRepeat['value'] < 1)
					{
						continue;
					}

					$count = 0;
					$intStartTime = $objEvents->startTime;
					$intEndTime = $objEvents->endTime;
					$strtotime = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

					while ($intEndTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
						{
							break;
						}

						$intStartTime = strtotime($strtotime, $intStartTime);
						$intEndTime = strtotime($strtotime, $intEndTime);

						// Skip events outside the scope
						if ($intEndTime < $intStart || $intStartTime > $intEnd)
						{
							continue;
						}

						$this->addEvent($objEvents, $intStartTime, $intEndTime, $strUrl, $intStart, $intEnd, $id);
					}
				}
			}
		}

		// Sort the array
		foreach (array_keys($this->arrEvents) as $key)
		{
			ksort($this->arrEvents[$key]);
		}

		// HOOK: modify the result set
		if (isset($GLOBALS['TL_HOOKS']['getAllEvents']) && is_array($GLOBALS['TL_HOOKS']['getAllEvents']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getAllEvents'] as $callback)
			{
				$this->import($callback[0]);
				$this->arrEvents = $this->$callback[0]->$callback[1]($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
			}
		}

		$arrIds = array();

		foreach($this->arrEvents as $intKey => $arrDays)
		{
			foreach($arrDays as $intStart => $arrEvents)
			{
				foreach($arrEvents as $arrEvent)
				{
					$arrIds[] = $arrEvent['id'];
				}
			}
		}

		// store events ids in session
		$session = \Session::getInstance()->getData();
		$session[CALENDARPLUS_SESSION_EVENT_IDS] = array();
		$session[CALENDARPLUS_SESSION_EVENT_IDS] = $arrIds;
		\Session::getInstance()->setData($session);

		return $this->arrEvents;
	}

	protected function getEventDetails($objEvent, $intStart, $intEnd, $strUrl, $intBegin, $intCalendar)
	{
		global $objPage;
		$span = \Calendar::calculateSpan($intStart, $intEnd);

		// Adjust the start time of a multi-day event (see #6802)
		if ($this->cal_noSpan && $span > 0 && $intStart < $intBegin && $intBegin < $intEnd)
		{
			$intStart = $intBegin;
		}

		$strDate = \Date::parse($objPage->dateFormat, $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
		$strMonth = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart)-1)];

		if ($span > 0)
		{
			$strDate = \Date::parse($objPage->dateFormat, $intStart) . ' - ' . \Date::parse($objPage->dateFormat, $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvent->addTime)
		{
			if ($span > 0)
			{
				$strDate = \Date::parse($objPage->datimFormat, $intStart) . ' - ' . \Date::parse($objPage->datimFormat, $intEnd);
			}
			elseif ($intStart == $intEnd)
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart);
			}
			else
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart) . ' - ' . \Date::parse($objPage->timeFormat, $intEnd);
			}
		}

		// Store raw data
		$arrEvent = $objEvent->row();

		// Overwrite some settings
		$arrEvent['time'] = $strTime;
		$arrEvent['date'] = $strDate;
		$arrEvent['day'] = $strDay;
		$arrEvent['month'] = $strMonth;
		$arrEvent['parent'] = $intCalendar;
		$arrEvent['link'] = $objEvent->title;
		$arrEvent['target'] = '';
		$arrEvent['title'] = specialchars($objEvent->title, true);
		$arrEvent['href'] = $this->generateEventUrl($objEvent, $strUrl);
		$arrEvent['class'] = ($objEvent->cssClass != '') ? ' ' . $objEvent->cssClass : '';
		$arrEvent['begin'] = $intStart;
		$arrEvent['end'] = $intEnd;
		$arrEvent['details'] = '';
		$arrEvent['startTimeFormated'] = \Date::parse($objPage->timeFormat, $objEvent->startTime);
		$arrEvent['endTimeFormated'] = \Date::parse($objPage->timeFormat, $objEvent->endTime);


		// modal
		if($this->cal_showInModal && $objEvent->source == 'default' && $this->cal_readerModule)
		{
			$arrEvent['modal'] = true;
			$arrEvent['modalTarget'] = '#' . EventsPlusHelper::getCSSModalID($this->cal_readerModule);
		}

		if($objEvent->promoter != '')
		{
			$objPromoter = CalendarPromotersModel::findByPk($objEvent->promoter);

			if($objPromoter !== null)
			{
				$arrEvent['promoterDetails'] = $objPromoter;

                if($objPromoter->website != '')
                {
                    $strWebsiteLink = $objPromoter->website;

                    // Add http:// to the website
                    if (($strWebsiteLink != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsiteLink))
                    {
                        $objPromoter->website = 'http://' . $strWebsiteLink;
                    }
                }
			}
		}

		$objEvent->docents = deserialize($objEvent->docents, true);

		if(is_array($objEvent->docents) && !empty($objEvent->docents))
		{
			$objDocents = CalendarDocentsModel::findMultipleByIds($objEvent->docents);

			if($objDocents !== null)
			{
				while($objDocents->next())
				{
					$arrEvent['docentList'][] = $objDocents->current();
				}
			}
		}


		$objEvent->eventtypes = deserialize($objEvent->eventtypes, true);

		if(is_array($objEvent->eventtypes) && !empty($objEvent->eventtypes))
		{
			$objEventTypes = CalendarEventtypesModel::findMultipleByIds($objEvent->eventtypes);

			if($objEventTypes !== null)
			{
				while($objEventTypes->next())
				{
					$arrEvent['eventtypeList'][] = $objEventTypes;
				}
			}
		}

		// time diff
		if($objEvent->endTime > $objEvent->startTime)
		{
			$objDateStartTime = new \DateTime();
			$objDateStartTime->setTimestamp($objEvent->startTime);
			$objDateEndTime = new \DateTime();
			$objDateEndTime->setTimestamp($objEvent->endTime);
			$arrEvent['timeDiff'] = $objDateStartTime->diff($objDateEndTime);
		}


		if($objEvent->website != '')
		{
			$arrEvent['websiteLink'] = $objEvent->website;

			// Add http:// to the website
			if (($objEvent->website != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $objEvent->website))
			{
				$arrEvent['websiteLink'] = 'http://' . $objEvent->website;
			}
		}


		// Override the link target
		if ($objEvent->source == 'external' && $objEvent->target)
		{
			$arrEvent['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
		}

		// Clean the RTE output
		if ($arrEvent['teaser'] != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$arrEvent['teaser'] = \String::toXhtml($arrEvent['teaser']);
			}
			else
			{
				$arrEvent['teaser'] = \String::toHtml5($arrEvent['teaser']);
			}
		}

		// Display the "read more" button for external/article links
		if ($objEvent->source != 'default')
		{
			$arrEvent['details'] = true;
		}

		// Compile the event text
		else
		{
			$objElement = \ContentModel::findPublishedByPidAndTable($objEvent->id, 'tl_calendar_events');

			if ($objElement !== null)
			{
				while ($objElement->next())
				{
					$arrEvent['details'] .= $this->getContentElement($objElement->current());
				}
			}
		}

		// Get todays start and end timestamp
		if ($this->intTodayBegin === null)
		{
			$this->intTodayBegin = strtotime('00:00:00');
		}
		if ($this->intTodayEnd === null)
		{
			$this->intTodayEnd = strtotime('23:59:59');
		}

		// Mark past and upcoming events (see #3692)
		if ($intEnd < $this->intTodayBegin)
		{
			$arrEvent['class'] .= ' bygone';
		}
		elseif ($intStart > $this->intTodayEnd)
		{
			$arrEvent['class'] .= ' upcoming';
		}
		else
		{
			$arrEvent['class'] .= ' current';
		}

		return $arrEvent;
	}
	
	/**
	 * Add an event to the array of active events
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 * @param integer
	 * @param integer
	 * @param integer
	 */
	protected function addEvent($objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intLimit, $intCalendar)
	{
		$span = \Calendar::calculateSpan($intStart, $intEnd);

		// Adjust the start time of a multi-day event (see #6802)
		if ($this->cal_noSpan && $span > 0 && $intStart < $intBegin && $intBegin < $intEnd)
		{
			$intStart = $intBegin;
		}

		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);

		$arrEvent = $this->getEventDetails($objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intCalendar);

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;


		// Multi-day event
		for ($i=1; $i<=$span && $intDate<=$intLimit; $i++)
		{
			// Only show first occurrence
			if ($this->cal_noSpan && $intDate >= $intBegin)
			{
				break;
			}

			$intDate = strtotime('+ 1 day', $intDate);
			$intNextKey = date('Ymd', $intDate);

			$this->arrEvents[$intNextKey][$intDate][] = $arrEvent;
		}
	}
}