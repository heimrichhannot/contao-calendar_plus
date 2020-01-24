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


class CalendarEventtypesModel extends \Model
{
    protected static $strTable = 'tl_calendar_eventtypes';

    /**
     * Find all item by pids
     *
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of models or null
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


        if (!$arrOptions['order'])
        {
            $arrOptions['order'] = 'title';
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Find all published items by their pids
     *
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|null A collection of models or null
     */
    public static function findPublishedByPids(array $arrPids = [], array $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrPids = array_map('intval', $arrPids);

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        if (!$arrOptions['order'])
        {
            $arrOptions['order'] = 'title';
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

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

        return static::findBy($arrColumns, [strval(strtolower($title))], $arrOptions);
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