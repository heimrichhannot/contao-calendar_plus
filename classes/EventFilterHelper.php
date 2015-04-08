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


class EventFilterHelper extends \Frontend
{
	protected static $strTable = 'tl_calendar_events';

	public static function getDocentSelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$objDocents = CalendarDocentsModel::findByPids($dc->objModule->cal_calendar);

		if($objDocents === null)
		{
			return $arrItems;
		}

		return $objDocents->fetchEach('title');
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

	public static function getPromoterSelectOptions(\DataContainer $dc)
	{
		$arrItems = array();

		if (!is_array($dc->objModule->cal_calendar) || empty($dc->objModule->cal_calendar))
		{
			return $arrItems;
		}

		$t = static::$strTable;

		$arrOptions['fields'][] = 'DISTINCT promoter';

		$arrOptions['column'][] = "$t.pid IN(" . implode(',', array_map('intval', $dc->objModule->cal_calendar)) . ")";

		$arrOptions['column'][] = 'city != ""';

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrOptions['column'][] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$arrOptions['order'] = 'promoter ASC';

		$arrOptions['foreignKey'] = 'tl_calendar_promoters.title';

		$objItems = static::getDatabaseResult($arrOptions);

		if($objItems === null) return $arrItems;

		$arrItems = $objItems->fetchEach('promoter');

		if (is_array($arrItems) && !empty($arrItems) && isset($arrOptions['foreignKey']))
		{
			$arrKey = explode('.', $arrOptions['foreignKey'], 2);
			$objOptions = \Database::getInstance()->query("SELECT id, " . $arrKey[1] . " AS value FROM " . $arrKey[0] . " WHERE tstamp>0 AND " . $arrKey[0] . ".id IN(" . implode(',', array_map('intval', $arrItems)) . ") ORDER BY value");
			$arrItems = array();

			while ($objOptions->next())
			{
				$arrItems[$objOptions->id] = $objOptions->value;
			}
		}

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


	public static function getValueByDca($value, $arrData)
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
}