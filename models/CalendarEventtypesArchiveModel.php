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


class CalendarEventtypesArchiveModel extends \Model
{
    protected static $strTable = 'tl_calendar_eventtypes_archive';

    /**
     * Find all item by title
     *
     * @param string $varValue   The title value
     * @param array  $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of models or null if the title was not found
     */
    public static function findByTitleAndPid(array $arrPids = [], $title, array $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t = static::$strTable;

        $arrPids = array_map('intval', $arrPids);

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "LOWER($t.title) LIKE ?";

        return static::findBy($arrColumns, strval(strtolower($title)), $arrOptions);
    }

    /**
     * Find all item by pids array
     *
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of models or null if no items found
     */
    public static function findByPids(array $arrPids = [], array $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t = static::$strTable;

        $arrPids = array_map('intval', $arrPids);

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Find all item that are not in pids array
     *
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of models or null if the title was not found
     */
    public static function findWithoutPids(array $arrPids = [], array $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t = static::$strTable;

        $arrPids = array_map('intval', $arrPids);

        $arrColumns[] = "($t.pid NOT IN (" . implode(',', $arrPids) . "))";

        return static::findBy($arrColumns, null, $arrOptions);
    }

    public function generateAlias()
    {
        $varValue = standardize(\StringUtil::restoreBasicEntities($this->title));

        $objAlias = static::findBy('alias', $varValue);

        // Check whether the alias exists
        if ($objAlias !== null)
        {
            if (!$this->id)
            {
                return $this;
            }

            $varValue .= '-' . $this->id;
        }

        $this->alias = $varValue;

        return $this;
    }
}