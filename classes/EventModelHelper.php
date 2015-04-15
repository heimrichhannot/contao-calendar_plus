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


class EventModelHelper extends EventsPlusHelper
{
	public static function createMySQLRegexpForMultipleIds($strField, array $arrIds)
	{
		$strRegexp = null;

		foreach($arrIds as $val)
		{
			if($strRegexp !== null)
			{
				$strRegexp .= " OR ";
			}

			$strRegexp .= "$strField REGEXP (':\"$val\"')";
		}

		return "($strRegexp)";
	}

}