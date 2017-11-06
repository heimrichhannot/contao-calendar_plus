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

use HeimrichHannot\DavAreasOfLaw\AreasOfLawModel;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\MemberPlus\MemberPlusMemberModel;
use Model\Collection;

class CalendarPlusEventsModel extends \CalendarEventsModel
{

    public static function getUniqueCityNamesByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.city != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'city';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

    public static function getUniquePromotersByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.promoter IS NOT NULL)";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return $objEvents;
        }

        $arrPromoters = [];

        while ($objEvents->next())
        {
            $arrPromoters = array_merge($arrPromoters, deserialize($objEvents->promoter, true));
        }

        $arrPromoters = array_unique($arrPromoters);

        return CalendarPromotersModel::findMultipleByIds($arrPromoters);
    }

    public static function getUniqueDocentsByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.docents != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'docents';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return null;
        }

        $arrDocents = [];

        while ($objEvents->next())
        {
            $arrDocents = array_merge($arrDocents, deserialize($objEvents->docents, true));
        }

        $arrDocents = array_unique($arrDocents);

        return CalendarDocentsModel::findMultipleByIds($arrDocents, ['order' => 'title']);
    }

    public static function getUniqueMemberDocentsByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.memberDocents != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'memberDocents';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return null;
        }

        $arrDocents = [];

        while ($objEvents->next())
        {
            $arrDocents = array_merge($arrDocents, deserialize($objEvents->memberDocents, true));
        }

        $arrDocents = array_unique($arrDocents);

        return MemberPlusMemberModel::findMultipleByIds($arrDocents, ['order' => 'lastname ASC']);
    }

    public static function getUniqueHostsByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.hosts != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'hosts';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return null;
        }

        $arrHosts = [];

        while ($objEvents->next())
        {
            $arrHosts = array_merge($arrHosts, deserialize($objEvents->hosts, true));
        }

        $arrHosts = array_unique($arrHosts);

        return CalendarDocentsModel::findMultipleByIds($arrHosts, ['order' => 'title']);
    }

    public static function getUniqueMemberHostsByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.memberHosts != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'memberHosts';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return null;
        }

        $arrHosts = [];

        while ($objEvents->next())
        {
            $arrHosts = array_merge($arrHosts, deserialize($objEvents->memberHosts, true));
        }

        $arrHosts = array_unique($arrHosts);

        return MemberPlusMemberModel::findMultipleByIds($arrHosts, ['order' => 'lastname ASC']);
    }


    public static function getUniquePromoterNamesByPids(array $arrPids = [], $currentOnly = true, $arrOptions = [])
    {
        if (!is_array($arrPids) || empty($arrPids))
        {
            return null;
        }

        $t    = static::$strTable;
        $time = time();

        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumns[] = "($t.promoter != '')";

        if ($currentOnly)
        {
            $arrColumns[] = "($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time)))";
        }

        $arrOptions['group'] = 'promoter';

        if (!BE_USER_LOGGED_IN)
        {
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        $objEvents = static::findBy($arrColumns, null, $arrOptions);

        if ($objEvents === null)
        {
            return $objEvents;
        }

        $arrPromoters = [];

        while ($objEvents->next())
        {
            $arrPromoters = array_merge($arrPromoters, deserialize($objEvents->promoter, true));
        }

        $arrPromoters = array_unique($arrPromoters);

        return CalendarPromotersModel::findMultipleByIds($arrPromoters);
    }

    /**
     * @param       $arrPids
     * @param       $intStart
     * @param       $intEnd
     * @param array $arrFilter
     * @param array $arrFilterOptions
     * @param array $arrFilterConfig
     * @param array $arrOptions
     *
     * @return int The number of total items
     */
    public static function countCurrentByPidAndFilter(
        $arrPids,
        $intStart,
        $intEnd,
        array $arrFilter = [],
        $arrFilterOptions = [],
        $arrFilterConfig = [],
        $arrOptions = []
    ) {
        return static::findCurrentByPidAndFilter($arrPids, $intStart, $intEnd, $arrFilter, $arrFilterOptions, $arrFilterConfig, $arrOptions, true);
    }

    /**
     * @param       $arrPids
     * @param       $intStart
     * @param       $intEnd
     * @param array $arrFilter
     * @param array $arrFilterOptions
     * @param array $arrFilterConfig
     * @param array $arrOptions
     * @param bool  $count
     *
     * @return \CalendarEventsModel|\CalendarEventsModel[]|Collection|null|int|static
     */
    public static function findCurrentByPidAndFilter(
        $arrPids,
        $intStart,
        $intEnd,
        array $arrFilter = [],
        array $arrFilterOptions = [],
        $arrFilterConfig = [],
        $arrOptions = [],
        $count = false
    ) {
        $t        = static::$strTable;
        $intStart = intval($intStart);
        $intEnd   = intval($intEnd);

        $arrPids = !is_array($arrPids) ? [$arrPids] : $arrPids;

        $arrFields = ["$t.*"];

        $arrFields[] = "IF(
                        DATEDIFF(FROM_UNIXTIME($t.endTime), FROM_UNIXTIME($t.startTime)) > 0 AND $t.startTime >= UNIX_TIMESTAMP() AND $t.endTime <= UNIX_TIMESTAMP(), 
                        UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(FROM_UNIXTIME(UNIX_TIMESTAMP(), '%%Y-%%m-%%d'),' ', FROM_UNIXTIME($t.startTime, '%%H:%%i:%%s')), '%%Y-%%m-%%d %%H:%%i:%%s')),
                        $t.startTime
                        ) as listTime";

        $arrValues    = [];
        $arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
        $arrColumnsOr = [];

        foreach ($arrFilter as $key => $value)
        {

            $arrValueOptions = (isset($arrFilterOptions[$key]) && is_array($arrFilterOptions[$key])) ? $arrFilterOptions[$key] : [];

            switch ($key)
            {
                case 'startDate':
                    if ($value && $value >= $intStart)
                    {
                        $intStart = $value;
                    }
                    break;
                case 'endDate':
                    if ($value && $value <= $intEnd)
                    {
                        $intEnd = strtotime(date('d.m.Y', $value) . ' 23:59:59'); // until last second of the day
                    }
                    break;

                case 'dates':
                    if ($value != '')
                    {
                        $strQuery   = '';
                        $valueArray = trimsplit(',', $value);

                        foreach ($valueArray as $key => $strDate)
                        {
                            $intDate = strtotime($strDate);
                            if ($key > 0)
                            {
                                $strQuery .= " OR ((($t.endDate IS NULL OR $t.endDate = '') AND $t.startDate = $intDate) OR (($t.startDate = '' OR $t.startDate <= $intDate) AND $t.endDate >= $intDate))";
                            }
                            else
                            {
                                $strQuery =
                                    "((($t.endDate IS NULL OR $t.endDate = '') AND $t.startDate = $intDate) OR (($t.startDate = '' OR $t.startDate <= $intDate) AND $t.endDate >= $intDate))";
                            }
                        }
                        $arrColumns[] = '(' . $strQuery . ')';
                    }
                    break;

                case 'promoter':
                case 'areasoflaw':
                    if ($value != '')
                    {
                        $valueArray = trimsplit(',', $value);

                        // permit intersections only
                        if (!empty($arrValueOptions))
                        {
                            $valueArray = array_intersect($valueArray, $arrValueOptions);
                        }

                        if (is_array($valueArray) && !empty($valueArray))
                        {
                            if ($arrFilterConfig['show_related'])
                            {
                                $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $valueArray, EVENTMODEL_CONDITION_OR);
                            }
                            else
                            {
                                $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $valueArray);
                            }

                        }
                    }
                    else
                    {
                        if (!empty($arrValueOptions))
                        {
                            $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $arrValueOptions);
                        }
                    }
                    break;
                case strrpos($key, 'eventtypes', -strlen($key)) !== false :
                    if ($value != '')
                    {
                        $valueArray = trimsplit(',', $value);
                        // permit intersections only
                        if (!empty($arrValueOptions))
                        {
                            $valueArray = array_intersect($valueArray, $arrValueOptions);
                        }

                        if (is_array($valueArray) && !empty($valueArray))
                        {
                            if ($arrFilterConfig['show_related'])
                            {
                                $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.eventtypes", $valueArray, EVENTMODEL_CONDITION_OR);
                            }
                            else
                            {
                                $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.eventtypes", $valueArray, EVENTMODEL_CONDITION_AND);
                            }

                        }
                    }
                    else
                    {
                        if (!empty($arrValueOptions))
                        {
                            $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.eventtypes", $arrValueOptions);
                        }
                    }
                    break;
                case 'docents':
                case 'hosts':
                    if ($value != '')
                    {
                        $valueArray = trimsplit(',', $value);

                        if (is_array($valueArray) && !empty($valueArray))
                        {
                            $arrDocents = [];
                            $arrMembers = [];
                            $arrHosts   = [];

                            foreach ($valueArray as $id)
                            {
                                // docent
                                if (\HeimrichHannot\Haste\Util\StringUtil::startsWith($id, 'd'))
                                {
                                    $arrDocents[] = substr($id, 1);
                                }
                                // members
                                else
                                {
                                    if (\HeimrichHannot\Haste\Util\StringUtil::startsWith($id, 'm'))
                                    {
                                        $arrMembers[] = substr($id, 1);
                                    }
                                    // hosts
                                    else
                                    {
                                        if (\HeimrichHannot\Haste\Util\StringUtil::startsWith($id, 'h'))
                                        {
                                            $arrHosts[] = substr($id, 1);
                                        }
                                    }
                                }
                            }

                            if (!empty($arrDocents))
                            {
                                if (is_array($valueArray) && !empty($valueArray))
                                {
                                    if ($arrFilterConfig['show_related'])
                                    {
                                        $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.docents", $arrDocents, EVENTMODEL_CONDITION_OR);
                                    }
                                    else
                                    {
                                        $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.docents", $arrDocents);
                                    }
                                }
                            }

                            if (!empty($arrMembers))
                            {
                                if (is_array($valueArray) && !empty($valueArray))
                                {
                                    if ($arrFilterConfig['show_related'] || $arrFilterConfig['combine_docents'])
                                    {
                                        $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberdocents", $arrMembers, EVENTMODEL_CONDITION_OR);

                                        if ($key = 'hosts' || $key == 'docents' && $arrFilterConfig['combine_docents'])
                                        {
                                            $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberhosts", $arrMembers, EVENTMODEL_CONDITION_OR);
                                        }
                                    }
                                    else
                                    {
                                        $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberdocents", $arrMembers);

                                        if ($key = 'hosts' || $key == 'docents' && $arrFilterConfig['combine_docents'])
                                        {
                                            $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberhosts", $arrMembers);
                                        }

                                    }
                                }
                            }

                            if (!empty($arrHosts) && ($key = 'hosts' || $key == 'docents' && $arrFilterConfig['cal_docent_combine']))
                            {
                                if (is_array($valueArray) && !empty($valueArray))
                                {
                                    if ($arrFilterConfig['show_related'])
                                    {
                                        $arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.hosts", $arrHosts, EVENTMODEL_CONDITION_OR);
                                    }
                                    else
                                    {
                                        $arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.hosts", $arrHosts);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'postal':
                    if ($value != '')
                    {
                        $arrColumns[] = "LEFT($t.postal, 1) = ?";
                        $arrValues[]  = $value;
                    }
                    break;
                case 'timeHours':
                    if ($value != '')
                    {
                        $arrColumns[] = "($t.$key >= $value AND $t.$key < ($value + 1))";
                    }
                    break;
                case 'q':
                    if ($value != '')
                    {

                        try
                        {
                            global $objPage;

                            $objSearch = \Search::searchFor(
                                $value,
                                ($arrFilterConfig['module']['queryType'] == 'or'),
                                is_array($arrFilterConfig['jumpTo']) ? $arrFilterConfig['jumpTo'] : [$objPage->id],
                                0,
                                0,
                                $arrFilterConfig['module']['fuzzy']
                            );

                            // title / teaser search as fallback
                            if ($objSearch->numRows < 1)
                            {
                                $arrColumns[] = "$t.title LIKE ? OR $t.teaser LIKE ?";
                                $arrValues[] = "%". $value . "%";
                                $arrValues[] = "%" . $value . "%";
                                break;
                            }

                            $arrUrls = $objSearch->fetchEach('url');

                            $strKeyWordColumns = "";

                            $n = 0;

                            foreach ($arrUrls as $i => $strAlias)
                            {
                                $strKeyWordColumns .= ($n > 0 ? " OR " : "") . "$t.alias = ?";
                                $arrParts          = parse_url($strAlias);
                                $arrValues[]       = basename($arrParts['path']);
                                $n++;
                            }

                            $arrColumns[] = "($strKeyWordColumns)";

                        } catch (\Exception $e)
                        {
                            \System::log('Website search failed: ' . $e->getMessage(), __METHOD__, TL_ERROR);
                        }
                    }
                    break;
                default:
                    if ($value != '')
                    {
                        $arrColumns[] = "$t.$key=?";
                        $arrValues[]  = $value;
                    }
            }
        }

        $arrColumns[] =
            "(($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring=1 AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))";

        if (!BE_USER_LOGGED_IN)
        {
            $time         = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        // for related search
        if (!empty($arrColumnsOr))
        {
            $arrColumns[] = implode(' OR ', $arrColumnsOr);
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "listTime ASC";

//            $arrOptions['order'] = "$t.startTime";
        }

        if ($count)
        {
            return static::countBy($arrColumns, $arrValues, $arrOptions);
        }

        $objStatement = \Database::getInstance()->prepare(
            sprintf("SELECT %s FROM $t WHERE %s ORDER BY %s", implode(',', $arrFields), implode(' AND ', $arrColumns), $arrOptions['order'])
        );

        if ($arrOptions['limit'] > 0)
        {
            $objStatement->limit($arrOptions['limit'], $arrOptions['offset'] ?: 0);
        }

        $objResult = $objStatement->execute($arrValues);

        if ($objResult !== null)
        {
            return Collection::createFromDbResult($objResult, $t);
        }

        return static::findBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * Find published events by their parent ID
     *
     * @param integer $intPid     The calendar ID
     * @param array   $arrOptions An optional options array
     *
     * @return \CalendarEventsModel|\Model\Collection|null A collection of models or null if there are no events
     */
    public static function findPublishedByPid($intPid, array $arrOptions = [])
    {
        $t          = static::$strTable;
        $arrColumns = ["$t.pid=?"];

        if (!BE_USER_LOGGED_IN)
        {
            $time         = \Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.startTime DESC";
        }

        return static::findBy($arrColumns, $intPid, $arrOptions);
    }

    /**
     * Find published events by id or alias
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return \Model|null The model or null if there is no event
     */
    public static function findPublishedEventsByIdOrAlias($varId, array $arrOptions = [])
    {
        $t          = static::$strTable;
        $arrColumns = ["($t.id=?) OR ($t.alias=?)"];

        if (!BE_USER_LOGGED_IN)
        {
            $time         = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        return static::findBy($arrColumns, [(is_numeric($varId) ? $varId : 0), $varId], $arrOptions);
    }

    public static function hasAtLeastOnePublishedPlacedSubEvent($config, $intId)
    {
        if (in_array('event_subscription', $config->getActiveModules()))
        {
            $objSubEvents = static::findPublishedSubEvents($intId);
            if ($objSubEvents !== null)
            {
                while ($objSubEvents->next())
                {
                    if ($objSubEvents->addSubscription)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Find published sub events by the parent's ID or alias
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return \Model|null The model or null if there is no event
     */
    public static function findPublishedSubEvents($varId, array $arrOptions = [])
    {
        $t          = static::$strTable;
        $arrColumns = ["($t.parentEvent=?)"];

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.startTime";
        }

        if (!BE_USER_LOGGED_IN)
        {
            $time         = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        return static::findBy($arrColumns, [(is_numeric($varId) ? $varId : 0), $varId], $arrOptions);
    }

    /**
     * Find published sub events by the parent's ID or alias
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrPids    An array of calendar IDs
     * @param array $arrOptions An optional options array
     *
     * @return \Model|null The model or null if there is no event
     */
    public static function findPublishedParentalEvents(array $arrOptions = [])
    {
        $t              = static::$strTable;
        $objEvents      = static::findAll();
        $parentalEvents = [];
        if ($objEvents !== null)
        {
            while ($objEvents->next())
            {
                if ($objEvents->parentEvent)
                {
                    if (!in_array($objEvents->parentEvent, $parentalEvents))
                    {
                        $parentalEvents[] = $objEvents->parentEvent;
                    }
                }
            }
        }
        $arrColumns = ["($t.id IN (" . implode(',', $parentalEvents) . "))"];

        if (!BE_USER_LOGGED_IN)
        {
            $time         = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        return static::findBy($arrColumns, [implode(',', $parentalEvents)], $arrOptions);
    }

    public static function hasTextOrTeaser($intId)
    {
        $objEvent = static::findByPk($intId);
        if ($objEvent !== null)
        {
            if ($objEvent->teaser)
            {
                return true;
            }
        }

        $objContent = \ContentModel::findPublishedByPidAndTable($intId, 'tl_calendar_events');

        return $objContent !== null;
    }

    public static function getPlacesLeft($intId, $database)
    {
        $objEvent = static::findByPk($intId);
        if ($objEvent !== null)
        {
            return $objEvent->placesTotal - static::getReservedPlaces($intId, $database);
        }

        return false;
    }

    public static function getReservedPlaces($intId, $database, $useMemberGroups = false)
    {
        $objEvent       = static::findByPk($intId);
        $reservedPlaces = 0;
        if ($objEvent !== null)
        {
            if ($useMemberGroups)
            {
                $objMembers = $database->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroup . '"%');

                return $objMembers->numRows;
            }
            else
            {
                $objSubscriber = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND value=?')->executeUncached('eventAlias', $objEvent->alias);
                if ($objSubscriber->numRows > 0)
                {
                    while ($objSubscriber->next())
                    {
                        $reservedPlaces += 1;
                    }
                }
            }
        }

        return $reservedPlaces;
    }

    public static function getCheckedInCount($intId)
    {
        $objEvent = static::findByPk($intId);

        if ($objEvent !== null)
        {
            $objMembers = \Database::getInstance()->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroupCheckedIn . '"%');

            return $objMembers->numRows;
        }

        return false;
    }

    public static function getPlacesLeftSubEvent($intId, $database)
    {
        $objEvent = static::findByPk($intId);
        if ($objEvent !== null)
        {
            return $objEvent->placesTotal - static::getReservedPlacesSubEvent($intId, $database);
        }

        return false;
    }

    public static function getReservedPlacesSubEvent($intId, $database)
    {
        $objEvent       = static::findByPk($intId);
        $reservedPlaces = 0;
        if ($objEvent !== null)
        {
            $objParentEvent = static::findByPk($objEvent->parentEvent);
            if ($objParentEvent !== null)
            {
                $objSubscriber = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND value=?')->executeUncached('eventAlias', $objParentEvent->alias);
                if ($objSubscriber->numRows > 0)
                {
                    while ($objSubscriber->next())
                    {
                        foreach (deserialize($objParentEvent->subEventFormFields, true) as $strSubEventFormField)
                        {
                            $objSubEvents = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND pid=?')->limit(1)->executeUncached(
                                $strSubEventFormField,
                                $objSubscriber->pid
                            );
                            if ($objSubEvents->numRows > 0)
                            {
                                $subEventIds = deserialize($objSubEvents->value, true);
                                if (in_array($intId, $subEventIds))
                                {
                                    $reservedPlaces += 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $reservedPlaces;
    }

    public static function getNonSubEventEvents($blnPublishedOnly = false)
    {
        if ($blnPublishedOnly)
        {
            return static::findBy(['(parentEvent IS NULL OR parentEvent="")', 'published=1'], []);
        }
        else
        {
            return static::findBy(['(parentEvent IS NULL OR parentEvent="")'], []);
        }
    }

    public static function hasContentElements($intId)
    {
        return (\ContentModel::findBy(['ptable=? AND pid=?'], ['tl_news', $intId]) !== null);
    }

    /**
     * Helper method to generate the alias for the current model
     *
     * @return $this
     */
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