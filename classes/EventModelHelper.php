<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package anwaltverein
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;

define('EVENTMODEL_CONDITION_OR', 'OR');
define('EVENTMODEL_CONDITION_AND', 'AND');

class EventModelHelper extends EventsPlusHelper
{
	public static function createMySQLRegexpForMultipleIds($strField, array $arrIds, $strCondition = EVENTMODEL_CONDITION_OR)
	{
		$strRegexp = null;

		if(!in_array($strCondition, array(EVENTMODEL_CONDITION_OR, EVENTMODEL_CONDITION_AND))) return '';

		foreach($arrIds as $val)
		{
			if($strRegexp !== null)
			{
				$strRegexp .= " $strCondition ";
			}

			$strRegexp .= "$strField REGEXP (':\"$val\"')";
			$strRegexp .= " OR $strField=$val"; // backwards compatibility (if field was no array before)
		}

		return "($strRegexp)";
	}

}