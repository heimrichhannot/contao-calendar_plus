<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package calendar_plus
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


use HeimrichHannot\Haste\Util\Arrays;

class ModuleEventListPlus extends EventsPlus
{
    /**
     * Current date object
     *
     * @var integer
     */
    protected $Date;

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_eventlist_plus';

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['eventlist_plus'][0]) . ' ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

        // Return if there are no calendars
        if (!is_array($this->cal_calendar) || empty($this->cal_calendar))
        {
            return '';
        }

        // Show the event reader if an item has been selected
        if (!($this->cal_showInModal || $this->useModal) && $this->cal_readerModule > 0 && (isset($_GET['events']) || (\Config::get('useAutoItem') && isset($_GET['auto_item']))))
        {
            return $this->getFrontendModule($this->cal_readerModule, $this->strColumn);
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        global $objPage;
        $blnClearInput = false;

        $intYear  = \Input::get('year');
        $intMonth = \Input::get('month');
        $intDay   = \Input::get('day');

        // Jump to the current period
        if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
        {
            switch ($this->cal_format)
            {
                case 'cal_year':
                    $intYear = date('Y');
                    break;

                case 'cal_month':
                    $intMonth = date('Ym');
                    break;

                case 'cal_day':
                    $intDay = date('Ymd');
                    break;
            }

            $blnClearInput = true;
        }

        $blnDynamicFormat = (!$this->cal_ignoreDynamic && in_array($this->cal_format, ['cal_day', 'cal_month', 'cal_year']));

        // Display year
        if ($blnDynamicFormat && $intYear)
        {
            $this->Date       = new \Date($intYear, 'Y');
            $this->cal_format = 'cal_year';
            $this->headline .= ' ' . date('Y', $this->Date->tstamp);
        }

        // Display month
        elseif ($blnDynamicFormat && $intMonth)
        {
            $this->Date       = new \Date($intMonth, 'Ym');
            $this->cal_format = 'cal_month';
            $this->headline .= ' ' . \Date::parse('F Y', $this->Date->tstamp);
        }

        // Display day
        elseif ($blnDynamicFormat && $intDay)
        {
            $this->Date       = new \Date($intDay, 'Ymd');
            $this->cal_format = 'cal_day';
            $this->headline .= ' ' . \Date::parse($objPage->dateFormat, $this->Date->tstamp);
        }

        // Display all events or upcoming/past events
        else
        {
            $this->Date = new \Date();
        }

        list($strBegin, $strEnd, $strEmpty) = $this->getDatesFromFormat($this->Date, $this->cal_format);

        $arrFilter       = [];
        $arrOptions      = [];
        $arrFilterConfig = [];

        if ($this->cal_filterModule)
        {
            $objFilterModule = \ModuleModel::findByPk($this->cal_filterModule);

            if ($objFilterModule !== null)
            {
                $objFilterModule = $this->prepareFilterModel($objFilterModule);
                $arrFilter       = $this->getFilter($objFilterModule);
//				$arrOptions = $this->getPossibleFilterOptions($objFilterModule);
                $arrFilterConfig['module']          = $objFilterModule->row();
                $arrFilterConfig['combine_docents'] = $objFilterModule->cal_docent_combine;

                if (!empty($arrFilter))
                {
                    $strEmpty = &$GLOBALS['TL_LANG']['eventlist']['listEmptyFilter'];
                }
            }
        }

        $arrQueryOptions = [];
        $total = 0;
        $id   = 'page_e' . $this->id;
        $page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

        if($this->cal_noSpan)
        {
            $total = $this->getAllEventsCount($this->cal_calendar, $strBegin, $strEnd, $arrFilter, $arrOptions, $arrFilterConfig);
            $offset = 0;

            // Overall limit
            if ($this->cal_limit > 0)
            {
                $total = min($this->cal_limit, $total);
            }

            // Pagination
            if ($this->perPage > 0)
            {
                //Set limit and offset
                $limit = $this->perPage;
                $offset += (max($page, 1) - 1) * $this->perPage;
                $skip = intval($this->skipFirst);

                // Overall limit
                if ($offset + $limit > $total + $skip) {
                    $limit = $total + $skip - $offset;
                }
            }

            $arrQueryOptions = ['limit' => $limit, 'offset' => $total < $this->perPage ? 0 : $offset];
        }


        // Get all events
        $arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd, $arrFilter, $arrOptions, $arrFilterConfig, $arrQueryOptions);

        $isRelatedList = false;

        if (empty($arrAllEvents) && $objFilterModule->cal_filterRelatedOnEmpty)
        {
            $arrFilterConfig['show_related'] = true;
            $arrAllEvents                    = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd, $arrFilter, $arrOptions, $arrFilterConfig, $arrQueryOptions);
            $isRelatedList                   = true;
        }

        $sort = ($this->cal_order == 'descending') ? 'krsort' : 'ksort';

        // Sort the days
        $sort($arrAllEvents);

        // Sort the events
        foreach (array_keys($arrAllEvents) as $key)
        {
            $sort($arrAllEvents[$key]);
        }

        $arrEvents         = [];
        $arrEventIds       = [];
        $arrParentEvents   = [];
        $arrParentEventIds = [];
        $arrSubEvents      = [];
        $dateBegin         = date('Ymd', $strBegin);
        $dateEnd           = date('Ymd', $strEnd);

        // Remove events outside the scope
        foreach ($arrAllEvents as $key => $days)
        {
            if ($key < $dateBegin || $key > $dateEnd)
            {
                continue;
            }

            foreach ($days as $day => $events)
            {
                foreach ($events as $event)
                {
                    $event['firstDay']  = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];
                    $event['firstDate'] = \Date::parse($objPage->dateFormat, $day);
                    $event['datetime']  = date('Y-m-d', $day);
                    $event['dateday']   = $day;

                    if (!$this->cal_ungroupSubevents)
                    {
                        // event is child event --> add parent event
                        if (($intParentEvent = $event['parentEvent']) > 0)
                        {
                            // add parent event
                            if (($arrParentEvent = $this->getParentEventDetails($intParentEvent, $event['pid'], $strBegin)) === null)
                            {
                                continue;
                            }
                            $arrParentEvent['firstDay']  = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];
                            $arrParentEvent['firstDate'] = \Date::parse($objPage->dateFormat, $day);
                            $arrParentEvent['datetime']  = date('Y-m-d', $day);
                            $arrParentEvent['dateday']   = $day;

                            if($this->cal_alwaysShowParents && !in_array($arrParentEvent['id'], $arrEventIds))
                            {
                                $arrEvents[] = $arrParentEvent;
                                $arrEventIds[] = $arrParentEvent['id'];
                                continue;
                            }

                            $arrParentEvents[$arrParentEvent['id']] = $arrParentEvent;
                            $arrParentEventIds[]                    = $arrParentEvent['id'];

                            // set parent event as href
                            $event['href']                               = $event['parentHref'];
                            $arrSubEvents[$intParentEvent][$event['id']] = $event;
                            continue;
                        }
                        // event is parent even --> add child events
                        else
                        {
                            $objChildEvents = CalendarPlusEventsModel::findPublishedSubEvents($event['id']);

                            if ($objChildEvents !== null)
                            {
                                while ($objChildEvents->next())
                                {
                                    $arrSubEvents[$event['id']][$objChildEvents->id] = $this->addSingleEvent($objChildEvents, $strBegin);
                                }
                            }
                        }
                    }

                    if(in_array($event['id'], $arrEventIds) && $this->cal_noSpan)
                    {
                        continue;
                    }

                    $arrEvents[]   = $event;
                    $arrEventIds[] = $event['id'];

                }
            }
        }

        $iteratorOffset = 0;

        if(!$this->cal_noSpan)
        {
            $total = count($arrEvents);
            $limit = $total;
        }

        if($this->perPage){

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
            {
                /** @var \PageError404 $objHandler */
                $objHandler = new $GLOBALS['TL_PTY']['error_404']();
                $objHandler->generate($objPage->id);
            }

            $offset = ($page - 1) * $this->perPage;
            $limit = min($this->perPage + $offset, $total);

            // load specific pagination template if infiniteScroll is used
            // otherwise keep standard pagination
            $objT = $this->cal_useInfiniteScroll ? new \FrontendTemplate('infinite_pagination') : null;

            if (!is_null($objT))
            {
                $objT->triggerText = $this->cal_changeTriggerText ? $this->cal_triggerText : $GLOBALS['TL_LANG']['eventlist']['loadMore'];
            }

            // Add the pagination menu
            $objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id, $objT);

            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        if(!$this->cal_noSpan){
            $iteratorOffset = $offset;
        }

        // add parent events to $arrEvents
        if (!empty($arrParentEventIds))
        {
            $arrEventIds = array_diff($arrParentEventIds, $arrEventIds);

            foreach ($arrParentEvents as $id => $event)
            {
                if (!in_array($id, $arrEventIds))
                {
                    continue;
                }

                $arrEvents[] = $event;
            }
        }

        unset($arrAllEvents);

        // sort events by their listTime to maintain child <-> parent time relation
        usort($arrEvents, function($a, $b) {
            return $a['listTime'] - $b['listTime'];
        });


        $strMonth         = '';
        $strDate          = '';
        $strEvents        = '';
        $monthCount       = 0;
        $dayCount         = 0;
        $eventCount       = 0;
        $headerCount      = 0;
        $headerMonthCount = 0;
        $imgSize          = false;

        // Override the default image size
        if ($this->imgSize != '')
        {
            $size = deserialize($this->imgSize);

            if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
            {
                $imgSize = $this->imgSize;
            }
        }



        // Parse events
        for ($i = $iteratorOffset; $i < $limit; $i++)
        {
            if(!isset($arrEvents[$i]))
            {
                break;
            }

            $event          = $arrEvents[$i];
            $blnIsLastEvent = false;

            // Last event on the current day
            if (($i + 1) == $limit || !isset($arrEvents[($i + 1)]['firstDate']) || $event['firstDate'] != $arrEvents[($i + 1)]['firstDate'])
            {
                $blnIsLastEvent = true;
            }

            $objTemplate = new \FrontendTemplate($this->cal_template);
            $objTemplate->setData($event);

            $objTemplate->lastItem = (($i + 1) == $limit) || ($arrEvents[($i + 1)]['month'] != $event['month']) || ($arrEvents[($i + 1)]['firstDate'] != $event['firstDate']);

            // Month header
            if ($strMonth != $event['month'])
            {
                $headerMonthCount        = 0;
                $objTemplate->newMonth   = true;
                $objTemplate->classMonth = ((($monthCount % 2) == 0) ? ' even' : ' odd') . (($monthCount == 0) ? ' first' : '') . (($event['firstDate'] == $arrEvents[($limit
                                                                                                                                                                       - 1)]['firstDate']) ? ' last' : '');
                $strMonth                = $event['month'];
                ++$monthCount;
            }

            // Day header
            if ($strDate != $event['firstDate'])
            {
                $headerCount              = 0;
                $objTemplate->header      = true;
                $objTemplate->classHeader = ((($dayCount % 2) == 0) ? ' even' : ' odd') . (($dayCount == 0) ? ' first' : '') . (($event['firstDate'] == $arrEvents[($limit
                                                                                                                                                                    - 1)]['firstDate']) ? ' last' : '');
                $objTemplate->firstHeader = $dayCount == 0;
                $strDate                  = $event['firstDate'];

                ++$dayCount;
            }

            if (isset($arrSubEvents[$event['id']]) && is_array($arrSubEvents[$event['id']]))
            {
                $strSubEvents = '';

                foreach ($arrSubEvents[$event['id']] as $subID => $arrSubEvent)
                {
                    $objSubEventTemplate = new \FrontendTemplate($this->cal_templateSubevent);
                    $objSubEventTemplate->setData($arrSubEvent);
                    $this->addEventDetailsToTemplate($objTemplate, $arrSubEvent, $headerCount, $eventCount, $imgSize);
                    $strSubEvents .= $objSubEventTemplate->parse() . "\n";
                }

                $objTemplate->subEvents = $strSubEvents;
            }

            $strClassList     =
                $event['class'] . ((($headerCount % 2) == 0) ? ' even' : ' odd') . (($headerCount == 0) ? ' first' : '') . ($blnIsLastEvent ? ' last' : '') . ' cal_'
                . $event['parent'];
            $strClassUpcoming =
                $event['class'] . ((($eventCount % 2) == 0) ? ' even' : ' odd') . (($eventCount == 0) ? ' first' : '') . ((($offset + $eventCount + 1) >= $limit) ? ' last' : '')
                . ' cal_' . $event['parent'];

            $this->addEventDetailsToTemplate($objTemplate, $event, $strClassList, $strClassUpcoming, $imgSize);

            $strEvents .= $objTemplate->parse();


            ++$eventCount;
            ++$headerCount;
        }


        $strEmpty                     = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
        $this->Template->emptyMessage = $strEmpty;

        // No events found
        if ($strEvents == '')
        {
            $strEvents             = $strEmpty;
            $this->Template->empty = true;
        }


        // See #3672
        $this->Template->headline  = $this->headline;
        $this->Template->events    = $strEvents;
        $this->Template->isRelated = $isRelatedList && !$this->Template->empty;

        // Clear the $_GET array (see #2445)
        if ($blnClearInput)
        {
            \Input::setGet('year', null);
            \Input::setGet('month', null);
            \Input::setGet('day', null);
        }
    }

}
