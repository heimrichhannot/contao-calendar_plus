<?php

namespace HeimrichHannot;

use \Contao\CalendarEventsModel as CalendarEventsModelOld;

if (!class_exists('CalendarEventsModel')) {

class CalendarEventsModel extends CalendarEventsModelOld
{
	
	/**
	 * Find published events by id or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedEventsByIdOrAlias($varId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=?) OR ($t.alias=?)");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}
	
	/**
	 * Find published sub events by the parent's ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedSubEventsByParentEventId($varId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.parentEvent=?)");

		$time = time();
		$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}
	
	public static function hasAtLeastOnePublishedPlacedSubEvent($config, $intId)
	{
		if (in_array('event_subscription', $config->getActiveModules())) {
			$objSubEvents = static::findPublishedSubEventsByParentEventId($intId);
			if ($objSubEvents !== null)
			{
				while ($objSubEvents->next())
				{
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
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of calendar IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedParentalEvents(array $arrOptions=array())
	{
		$t = static::$strTable;
		$objEvents = \CalendarEventsModel::findAll();
		$parentalEvents = array();
		if ($objEvents !== null) {
			while ($objEvents->next())
			{
				if ($objEvents->parentEvent)
					if (!in_array($objEvents->parentEvent, $parentalEvents))
						$parentalEvents[] = $objEvents->parentEvent;
			}
		}
		$arrColumns = array("($t.id IN (" . implode(',', $parentalEvents) . "))");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
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
	
	public static function getPlacesLeft($intId, $database) {
		$objEvent = static::findByPk($intId);
		if ($objEvent !== null) {
			return $objEvent->placesTotal - static::getReservedPlaces($intId, $database);
		}
		return false;
	}
	
	public static function getReservedPlaces($intId, $database, $useMemberGroups = false) {
		$objEvent = static::findByPk($intId);
		$reservedPlaces = 0;
		if ($objEvent !== null) {
			if ($useMemberGroups)
			{
				$objMembers = $database->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroup . '"%');
				return $objMembers->numRows;
			}
			else
			{
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
	
	public static function getCheckedInCount($intId) {
		$objEvent = static::findByPk($intId);
		
		if ($objEvent !== null) {
			$objMembers = \Database::getInstance()->prepare('SELECT * FROM tl_member WHERE groups LIKE ?')->execute('%"' . $objEvent->memberGroupCheckedIn . '"%');
			return $objMembers->numRows;
		}
		return false;
	}
	
	public static function getPlacesLeftSubEvent($intId, $database) {
		$objEvent = static::findByPk($intId);
		if ($objEvent !== null) {
			return $objEvent->placesTotal - static::getReservedPlacesSubEvent($intId, $database);
		}
		return false;
	}
	
	public static function getReservedPlacesSubEvent($intId, $database) {
		$objEvent = static::findByPk($intId);
		$reservedPlaces = 0;
		if ($objEvent !== null) {
			$objParentEvent = \CalendarEventsModel::findByPk($objEvent->parentEvent);
			if ($objParentEvent !== null) {
				$objSubscriber = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND value=?')->executeUncached('eventAlias', $objParentEvent->alias);
				if ($objSubscriber->numRows > 0) {
					while ($objSubscriber->next()) {
						$objSubEvents = $database->prepare('SELECT * FROM tl_formdata_details WHERE ff_name=? AND pid=?')->limit(1)->executeUncached($objParentEvent->subEventFormField, $objSubscriber->pid);
						if ($objSubEvents->numRows > 0) {
							$subEventIds = deserialize($objSubEvents->value, true);
							if (in_array($intId, $subEventIds))
								$reservedPlaces += 1;
						}
					}
				}
			}
		}
		return $reservedPlaces;
	}
	
	public static function hasContentElements($intId) {
		return (\ContentModel::findBy(array('ptable=? AND pid=?'), array('tl_news', $intId)) !== null);
	}
	
}
}