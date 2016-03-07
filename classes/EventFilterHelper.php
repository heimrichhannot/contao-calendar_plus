<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendarplus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


use HeimrichHannot\MemberPlus\MemberPlusMemberModel;

class EventFilterHelper extends \Frontend
{
	protected static $strTable = 'tl_calendar_events';

	protected static $strTemplate = 'eventfilter_eventtypes_archives';

	public static function getDocentSelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$objDocents = CalendarPlusEventsModel::getUniqueDocentsByPids($dc->objModule->cal_calendar);
		$objMemberDocents = CalendarPlusEventsModel::getUniqueMemberDocentsByPids($dc->objModule->cal_calendar);
		
		if($objDocents !== null)
		{
			while($objDocents->next())
			{
				$arrOrder['m' . $objMemberDocents->id] = $objDocents->title;
				$arrItems['d' . $objDocents->id] = $objDocents->title;
			}
		}

		$arrOrder = array();
		
		if($objMemberDocents !== null)
		{
			while($objMemberDocents->next())
			{
				$arrTitle = array($objMemberDocents->academicTitle, $objMemberDocents->firstname, $objMemberDocents->lastname);

				if (empty($arrTitle)) {
					continue;
				}

				$arrOrder['m' . $objMemberDocents->id] = $objMemberDocents->lastname;
				$arrItems['m' . $objMemberDocents->id] = trim(implode(' ', $arrTitle));
			}
		}

		// sort by lastname
		asort($arrOrder);
		
		$arrItems = array_replace($arrOrder, $arrItems);

		return $arrItems;
	}

	public static function getEventTypesSelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$objArchives = CalendarEventtypesArchiveModel::findByPids($dc->objModule->cal_calendar);

		if($objArchives === null)
		{
			return $arrItems;
		}

		$objEvenTypes = CalendarEventtypesModel::findByPids($objArchives->fetchEach('id'));

		if($objEvenTypes === null)
		{
			return $arrItems;
		}

		return $objEvenTypes->fetchEach('title');
	}

	public static function getEventTypesFieldsByArchive(\DataContainer $dc)
	{
		$arrItems = array();

		if($dc->objModule->cal_combineEventTypesArchive === "1")
		{
			return static::getEventTypesSelectOptions($dc);
		}
		else
		{
			$arrEventTypesArchives = deserialize($dc->objModule->cal_eventTypesArchive);
			if($arrEventTypesArchives === null || empty($arrEventTypesArchives)) {
				return $arrItems;
			}

			$arrEventTypesArchivesMultiple = deserialize($dc->objModule->cal_eventTypesArchiveMultiple, true);

			foreach($arrEventTypesArchives as $value)
			{
				$arrOptions = array();
				$arrSelected = array();
				$varMultiple = false;

				$objArchive = CalendarEventtypesArchiveModel::findByIdOrAlias($value);
				if ($objArchive === null) {
					return $arrItems;
				}

				$objEventTypes = CalendarEventtypesModel::findByPids(array($value));

				if ($objEventTypes === null)
				{
					return $arrItems;
				}

				while($objEventTypes->next())
				{
					$objEventtypesArchive = $objEventTypes->getRelated('pid');

					if($objEventtypesArchive === null) continue;

					$strClass  = (($objEventTypes->cssClass != '') ? ' ' . $objEventTypes->cssClass : '');
					$strClass .= (($objEventtypesArchive->cssClass != '') ? ' ' . $objEventtypesArchive->cssClass : '');

					$objEventTypes->class = $strClass;

					$arrOptions[$objEventTypes->id] = $objEventTypes->current();
				}

				$strName = sprintf("eventtypes_%d", $value);
				
				$objTemplate = new \FrontendTemplate(static::$strTemplate);
				$objTemplate->name = $strName . '[]';
				
				$arrSubmitted = \Input::get($strName);
				
				if(is_array($arrSubmitted) && !empty($arrSubmitted))
				{
					$arrSelected = array_intersect($arrSubmitted, array_keys($arrOptions));
				}

				if(in_array($value, $arrEventTypesArchivesMultiple) && is_array($arrEventTypesArchivesMultiple) && !empty($arrEventTypesArchivesMultiple)) {
					$varMultiple = true;
				}

				$objTemplate->arrSelected = $arrSelected;
				$objTemplate->alias = $objArchive->alias;
				$objTemplate->label = "$objArchive->title";
				$objTemplate->options = $arrOptions;
				$objTemplate->multiple = $varMultiple;

				$arrArchives[] = array
				(
					'options' => array_keys($arrOptions),
					'id' => $objArchive->id,
					'output' => $objTemplate->parse(),
				);
			}
		}
		
		return $arrArchives;
	}


	public static function getPromoterSelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$objPromoters = CalendarPlusEventsModel::getUniquePromotersByPids($dc->objModule->cal_calendar);

		if($objPromoters !== null)
		{
			$arrItems = $objPromoters->fetchEach('title');
		}

		// sort
		asort($arrItems);

		return $arrItems;
	}

	public static function getCitySelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$arrItems = array();

		$t = static::$strTable;

		$arrOptions['fields'][] = 'DISTINCT city';

		$arrOptions['column'][] = "$t.pid IN(" . implode(',', array_map('intval', $dc->objModule->cal_calendar)) . ")";

		$arrOptions['column'][] = 'city != ""';

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrOptions['column'][] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$arrOptions['order'] = 'city ASC';

		$objItems = static::getDatabaseResult($arrOptions);

		if($objItems === null) return $arrItems;

		$arrItems = $objItems->fetchEach('city');

		return $arrItems;
	}


	public static function getDatabaseResult($arrOptions=array())
	{
		$t = static::$strTable;

		$strQuery = "SELECT " . (is_array($arrOptions['fields']) ? implode(", ", $arrOptions['fields']) : '*') . " FROM $t";

		// Where condition
		if ($arrOptions['column'] !== null)
		{
			$strQuery .= " WHERE " . (is_array($arrOptions['column']) ? implode(" AND ", $arrOptions['column']) :  $t . '.' . $arrOptions['column'] . "=?");
		}

		// Group by
		if ($arrOptions['group'] !== null)
		{
			$strQuery .= " GROUP BY " . $arrOptions['group'];
		}

		// Having (see #6446)
		if ($arrOptions['having'] !== null)
		{
			$strQuery .= " HAVING " . $arrOptions['having'];
		}

		// Order by
		if ($arrOptions['order'] !== null)
		{
			$strQuery .= " ORDER BY " . $arrOptions['order'];
		}

		return \Database::getInstance()->prepare($strQuery)->execute();
	}


	public function getValueByDca($value, $arrData)
	{
		$value = deserialize($value);
		$rgxp = $arrData['eval']['rgxp'];
		$opts = $arrData['options'];
		$rfrc = $arrData['reference'];

		$rgxp = $arrData['eval']['rgxp'];

		if ($rgxp == 'date' && \Validator::isDate($value))
		{
			// Validate the date (see #5086)
			try
			{
				$objDate = new \Date($value);
				$value = $objDate->tstamp;
			}
			catch (\OutOfBoundsException $e){}
		}
		elseif ($rgxp == 'time' && \Validator::isTime($value))
		{
			// Validate the date (see #5086)
			try
			{
				$objDate = new \Date($value);
				$value = $objDate->tstamp;
			}
			catch (\OutOfBoundsException $e){}
		}
		elseif ($rgxp == 'datim' && \Validator::isDatim($value))
		{
			// Validate the date (see #5086)
			try
			{
				$objDate = new \Date($value);
				$value = $objDate->tstamp;
			}
			catch (\OutOfBoundsException $e){}
		}
		elseif (is_array($value))
		{
			$value = static::flattenArray($value);
			
			$value = array_filter($value); // remove empty elements

			$value = implode(', ', $value);
		}
		elseif (is_array($opts) && array_is_assoc($opts))
		{
			$value = isset($opts[$value]) ? $opts[$value] : $value;
		}
		elseif (is_array($rfrc))
		{
			$value = isset($rfrc[$value]) ? ((is_array($rfrc[$value])) ? $rfrc[$value][0] : $rfrc[$value]) : $value;
		}
		else
		{
			$value = $value;
		}

		// Convert special characters (see #1890)
		return specialchars($value);
	}

	public static function flattenArray(array $array) {
		$return = array();
		array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
		return $return;
	}
}