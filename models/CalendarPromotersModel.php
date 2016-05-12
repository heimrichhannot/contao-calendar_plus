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


class CalendarPromotersModel extends \Model
{
	protected static $strTable = 'tl_calendar_promoters';

	protected static $strCode = 'code';

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

	/**
	 * Helper method to generate the alias for the current model
	 * @return $this
	 */
	public function generateAlias()
	{
		$varValue = standardize(\String::restoreBasicEntities($this->title));

		$objAlias = static::findBy('alias', $varValue);

		// Check whether the alias exists
		if ($objAlias !== null) {
			if(!$this->id) return $this;
			$varValue .= '-' . $this->id;
		}

		$this->alias = $varValue;

		return $this;
	}

	/**
	 * Find a single record by its code
	 *
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if the result is empty
	 */
	public static function findByCode($varValue, array $arrOptions=array())
	{
		$arrOptions = array_merge
		(
			array
			(
				'limit'  => 1,
				'column' => static::$strCode,
				'value'  => $varValue,
				'return' => 'Model'
			),

			$arrOptions
		);

		return static::find($arrOptions);
	}

	public static function findPublishedByPidsAndTypes(array $arrPids, array $arrTypes, $arrOptions=array())
	{
		$t = static::$strTable;
		$time = time();

		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		$arrColumns[] = "$t.type IN('" . implode("','", $arrTypes) . "')";

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if(!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.title";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
