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

use HeimrichHannot\DavAreasOfLaw\AreasOfLawModel;
use HeimrichHannot\MemberPlus\MemberPlusMemberModel;

class CalendarPlusEventsModel extends \CalendarEventsModel
{

	public static function getUniqueCityNamesByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.city != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		$arrOptions['group'] = 'city';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}

	public static function getUniquePromotersByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.promoter != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		$arrOptions['group'] = 'promoter';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$objEvents = static::findBy($arrColumns, null, $arrOptions);

		if($objEvents === null) return $objEvents;

		$arrPromoters = array();

		while($objEvents->next())
		{
			$arrPromoters = array_merge($arrPromoters, deserialize($objEvents->promoter, true));
		}

		$arrPromoters = array_unique($arrPromoters);

		return CalendarPromotersModel::findMultipleByIds($arrPromoters);
	}

	public static function getUniqueDocentsByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.docents != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		$arrOptions['group'] = 'docents';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$objEvents = static::findBy($arrColumns, null, $arrOptions);

		if($objEvents === null) return null;

		$arrDocents = array();

		while($objEvents->next())
		{
			$arrDocents = array_merge($arrDocents, deserialize($objEvents->docents, true));
		}

		$arrDocents = array_unique($arrDocents);

		return CalendarDocentsModel::findMultipleByIds($arrDocents, array('order' => 'title'));
	}

	public static function getUniqueMemberDocentsByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.memberDocents != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		$arrOptions['group'] = 'memberDocents';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$objEvents = static::findBy($arrColumns, null, $arrOptions);

		if($objEvents === null) return null;

		$arrDocents = array();

		while($objEvents->next())
		{
			$arrDocents = array_merge($arrDocents,  deserialize($objEvents->memberDocents, true));
		}

		$arrDocents = array_unique($arrDocents);

		return MemberPlusMemberModel::findMultipleByIds($arrDocents, array('order' => 'lastname ASC'));
	}


	public static function getUniquePromoterNamesByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.promoter != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		$arrOptions['group'] = 'promoter';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$objEvents = static::findBy($arrColumns, null, $arrOptions);

		if($objEvents === null) return $objEvents;

		$arrPromoters = array();

		while($objEvents->next())
		{
			$arrPromoters = array_merge($arrPromoters, deserialize($objEvents->promoter, true));
		}

		$arrPromoters = array_unique($arrPromoters);
		
		return CalendarPromotersModel::findMultipleByIds($arrPromoters);
	}


	public static function getUniqueAreasOfLawByPids(array $arrPids=array(), $currentOnly=true, $arrOptions = array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$time = time();

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumns[] = "($t.areasoflaw != '')";

		if($currentOnly)
		{
			$arrColumns[] = "($t.startDate >= $time)";
		}

		//$arrOptions['group'] = 'promoter';

		if (!BE_USER_LOGGED_IN) {
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$objEvents = static::findBy($arrColumns, null, $arrOptions);

		if($objEvents === null) return $objEvents;

		$arrAreasOfLaw = array();

		while($objEvents->next())
		{
			$arrAreasOfLaw = array_merge($arrAreasOfLaw, deserialize($objEvents->areasoflaw, true));
		}
		
		$arrAreasOfLaw = array_unique($arrAreasOfLaw);

		return AreasOfLawModel::findMultipleByIds($arrAreasOfLaw);
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
			if (!$this->id) return $this;
			$varValue .= '-' . $this->id;
		}

		$this->alias = $varValue;

		return $this;
	}

	public static function countCurrentByPidAndFilter($arrPids, $intStart, $intEnd, array $arrFilter = array(), $arrFilterOptions = array(), $arrFilterConfig = array(), $arrOptions = array())
	{
		return static::findCurrentByPidAndFilter($arrPids, $intStart, $intEnd, $arrFilter, $arrFilterOptions, $arrFilterConfig, $arrOptions, true);
	}

	public static function findCurrentByPidAndFilter($arrPids, $intStart, $intEnd, array $arrFilter = array(), array $arrFilterOptions = array(), $arrFilterConfig = array(), $arrOptions = array(), $count = false)
	{
		$t        = static::$strTable;
		$intStart = intval($intStart);
		$intEnd   = intval($intEnd);

		$arrPids = !is_array($arrPids) ? array($arrPids) : $arrPids;

		$arrColumns[] = "($t.pid IN (" . implode(',', $arrPids) . "))";
		$arrColumnsOr = array();

		foreach ($arrFilter as $key => $value) {

			$arrValueOptions = (isset($arrFilterOptions[$key]) && is_array($arrFilterOptions[$key])) ? $arrFilterOptions[$key] : array();
			
			switch ($key) {
				case 'startDate':
					if ($value && $value >= $intStart) {
						$intStart = $value;
					}
					break;
				case 'endDate':
					if ($value && $value <= $intEnd) {
						$intEnd = strtotime(date('d.m.Y', $value) . ' 23:59:59'); // until last second of the day
					}
					break;

				case 'dates':
					if ($value != '')
					{
						$strQuery = '';
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
								$strQuery = "((($t.endDate IS NULL OR $t.endDate = '') AND $t.startDate = $intDate) OR (($t.startDate = '' OR $t.startDate <= $intDate) AND $t.endDate >= $intDate))";
							}
						}
						$arrColumns[] = '(' . $strQuery . ')';
					}
					break;

				case 'promoter':
				case 'areasoflaw':
					if ($value != '') {
						$valueArray = trimsplit(',', $value);

						// permit intersections only
						if(!empty($arrValueOptions))
						{
							$valueArray = array_intersect($valueArray, $arrValueOptions);
						}
						
						if(is_array($valueArray) && !empty($valueArray))
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
					else if(!empty($arrValueOptions))
					{
						$arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $arrValueOptions);
					}
					break;
				case strrpos($key, 'eventtypes', -strlen($key)) !== FALSE :
					if ($value != '') {
						$valueArray = trimsplit(',', $value);
						// permit intersections only
						if(!empty($arrValueOptions))
						{
							$valueArray = array_intersect($valueArray, $arrValueOptions);
						}
						
						if(is_array($valueArray) && !empty($valueArray))
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
					else if(!empty($arrValueOptions))
					{
						$arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.eventtypes", $arrValueOptions);
					}
					break;
				case 'docents':
					if ($value != '') {
						$valueArray = trimsplit(',', $value);

						if(is_array($valueArray) && !empty($valueArray))
						{
							$arrDocents = array();
							$arrMemberDocents = array();

							foreach($valueArray as $id)
							{
								// docent
								if(substr($id, 0, 1) == 'd'){
									$arrDocents[] = substr($id,1);
								}
								// memberdocent
								else if(substr($id, 0, 1) == 'm'){
									$arrMemberDocents[] = substr($id,1);
								}
							}

							if(!empty($arrDocents))
							{
								if(is_array($valueArray) && !empty($valueArray))
								{
									if ($arrFilterConfig['show_related'])
									{
										$arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $arrDocents, EVENTMODEL_CONDITION_OR);
									}
									else
									{
										$arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.$key", $arrDocents);
									}
								}
							}

							if(!empty($arrMemberDocents))
							{
								if(is_array($valueArray) && !empty($valueArray))
								{
									if ($arrFilterConfig['show_related'])
									{
										$arrColumnsOr[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberdocents", $arrMemberDocents, EVENTMODEL_CONDITION_OR);
									}
									else
									{
										$arrColumns[] = EventModelHelper::createMySQLRegexpForMultipleIds("$t.memberdocents", $arrMemberDocents);
									}
								}
							}

						}
					}
					break;
				case 'postal':
					if ($value != '') {
						$arrColumns[] = "LEFT($t.postal, 1) = ?";
						$arrValues[]  = $value;
					}
					break;
				case 'timeHours':
					if ($value != '') {
						$arrColumns[] = "($t.$key >= $value AND $t.$key < ($value + 1))";
					}
					break;
				case 'q':
						if ($value != '' && is_array($arrFilterConfig['jumpTo'])) {

							try
							{
								$objSearch = \Search::searchFor($value, ($arrFilterConfig['module']['queryType'] == 'or'), $arrFilterConfig['jumpTo'], 0, 0, $arrFilterConfig['module']['fuzzy']);

								// return if keyword not found
								if($objSearch->numRows < 1) return null;

								$arrUrls = $objSearch->fetchEach('url');

								$strKeyWordColumns = "";

								$n = 0;

								foreach($arrUrls as $i => $strAlias)
								{
									$strKeyWordColumns .= ($n > 0 ? " OR " : "") . "$t.alias = ?";
									$arrValues[] = basename($strAlias);
									$n++;
								}

								$arrColumns[] = "($strKeyWordColumns)";

							}
							catch (\Exception $e)
							{
								\System::log('Website search failed: ' . $e->getMessage(), __METHOD__, TL_ERROR);
							}
						}
					break;
				default:
					if ($value != '') {
						$arrColumns[] = "$t.$key=?";
						$arrValues[]  = $value;
					}
			}
		}

		$arrColumns[] = "(($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring=1 AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))";

		if (!BE_USER_LOGGED_IN)
		{
			$time         = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}
		
		// for related search
		if(!empty($arrColumnsOr))
		{
			$arrColumns[] = implode(' OR ', $arrColumnsOr);
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.startTime";
		}
		
		if($count)
		{
			return static::countBy($arrColumns, $arrValues, $arrOptions);
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
	public static function findPublishedByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.startTime DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}

	/**
	 * Find published events by id or alias
	 *
	 * @param mixed $varId The numeric ID or alias name
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedEventsByIdOrAlias($varId, array $arrOptions = array())
	{
		$t          = static::$strTable;
		$arrColumns = array("($t.id=?) OR ($t.alias=?)");

		if (!BE_USER_LOGGED_IN) {
			$time         = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}

	/**
	 * Find published sub events by the parent's ID or alias
	 *
	 * @param mixed $varId The numeric ID or alias name
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedSubEventsByParentEventId($varId, array $arrOptions = array())
	{
		$t          = static::$strTable;
		$arrColumns = array("($t.parentEvent=?)");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.startTime";
		}

		if (!BE_USER_LOGGED_IN) {
			$time         = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}

	public static function hasAtLeastOnePublishedPlacedSubEvent($config, $intId)
	{
		if (in_array('event_subscription', $config->getActiveModules())) {
			$objSubEvents = static::findPublishedSubEventsByParentEventId($intId);
			if ($objSubEvents !== null) {
				while ($objSubEvents->next()) {
					if ($objSubEvents->addSubscription)
						return true;
				}
			}
		}

		return false;
	}

	/**
	 * Find published sub events by the parent's ID or alias
	 *
	 * @param mixed $varId The numeric ID or alias name
	 * @param array $arrPids An array of calendar IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedParentalEvents(array $arrOptions = array())
	{
		$t              = static::$strTable;
		$objEvents      = static::findAll();
		$parentalEvents = array();
		if ($objEvents !== null) {
			while ($objEvents->next()) {
				if ($objEvents->parentEvent)
					if (!in_array($objEvents->parentEvent, $parentalEvents))
						$parentalEvents[] = $objEvents->parentEvent;
			}
		}
		$arrColumns = array("($t.id IN (" . implode(',', $parentalEvents) . "))");

		if (!BE_USER_LOGGED_IN) {
			$time         = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array(implode(',', $parentalEvents)), $arrOptions);
	}

	public static function hasTextOrTeaser($intId)
	{
		$objEvent = static::findByPk($intId);
		if ($objEvent !== null)
			if ($objEvent->teaser)
				return true;

		$objContent = \ContentModel::findPublishedByPidAndTable($intId, 'tl_calendar_events');

		return $objContent !== null;
	}

	public static function getPlacesLeft($intId, $database)
	{
		$objEvent = static::findByPk($intId);
		if ($objEvent !== null) {
			return $objEvent->placesTotal - static::getReservedPlaces($intId, $database);
		}

		return false;
	}

	public static function getReservedPlaces($intId, $database, $useMemberGroups = false)
	{
		$objEvent       = static::findByPk($intId);
		$reservedPlaces = 0;
		if ($objEvent !== null) {
			if ($useMemberGroups) {
				$objMembers = $database->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroup . '"%');

				return $objMembers->numRows;
			} else {
				$objSubscriber = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND value=?')->executeUncached('eventAlias', $objEvent->alias);
				if ($objSubscriber->numRows > 0) {
					while ($objSubscriber->next()) {
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

		if ($objEvent !== null) {
			$objMembers = \Database::getInstance()->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroupCheckedIn . '"%');

			return $objMembers->numRows;
		}

		return false;
	}

	public static function getPlacesLeftSubEvent($intId, $database)
	{
		$objEvent = static::findByPk($intId);
		if ($objEvent !== null) {
			return $objEvent->placesTotal - static::getReservedPlacesSubEvent($intId, $database);
		}

		return false;
	}

	public static function getReservedPlacesSubEvent($intId, $database)
	{
		$objEvent       = static::findByPk($intId);
		$reservedPlaces = 0;
		if ($objEvent !== null) {
			$objParentEvent = static::findByPk($objEvent->parentEvent);
			if ($objParentEvent !== null) {
				$objSubscriber = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND value=?')->executeUncached('eventAlias', $objParentEvent->alias);
				if ($objSubscriber->numRows > 0) {
					while ($objSubscriber->next()) {
						foreach (deserialize($objParentEvent->subEventFormFields, true) as $strSubEventFormField) {
							$objSubEvents = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND pid=?')
								->limit(1)->executeUncached($strSubEventFormField, $objSubscriber->pid);
							if ($objSubEvents->numRows > 0) {
								$subEventIds = deserialize($objSubEvents->value, true);
								if (in_array($intId, $subEventIds))
									$reservedPlaces += 1;
							}
						}
					}
				}
			}
		}

		return $reservedPlaces;
	}

	public static function hasContentElements($intId)
	{
		return (\ContentModel::findBy(array('ptable=? AND pid=?'), array('tl_news', $intId)) !== null);
	}

}