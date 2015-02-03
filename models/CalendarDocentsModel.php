<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


class CalendarDocentsModel extends \Model
{
	protected static $strTable = 'tl_calendar_docents';


	public function generateAlias()
	{
		$varValue = standardize(\String::restoreBasicEntities($this->title));

		$objAlias = static::findBy('alias', $varValue);

		// Check whether the alias exists
		if ($objAlias->numRows > 1)
		{
			if(!$this->id) return $this;

			$varValue .= '-' . $this->id;
		}

		$this->alias = $varValue;

		return $this;
	}

	/**
	 * Find all item by title
	 *
	 * @param string  $varValue   The title value
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if the title was not found
	 */
	public static function findByTitle($title, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findBy(array("LOWER($t.title) LIKE '" . strval(strtolower($title)) . "'"), null, $arrOptions);
	}
}