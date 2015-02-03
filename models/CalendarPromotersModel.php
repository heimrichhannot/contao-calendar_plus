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
	 * Helper method to generate the alias for the current model
	 * @return $this
	 */
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
	 * Find a single record by its code
	 *
	 * @param mixed $varValue   The property value
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if the result is empty
	 */
	public static function findByCode($varValue, array $arrOptions=array())
	{
		// Try to load from the registry
		if (empty($arrOptions))
		{
			$objModel = \Model\Registry::getInstance()->fetch(static::$strCode, $varValue);

			if ($objModel !== null)
			{
				return $objModel;
			}
		}

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

}