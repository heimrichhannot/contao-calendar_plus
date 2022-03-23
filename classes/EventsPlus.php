<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package ${CARET}
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


use Events;
use HeimrichHannot\MemberPlus\MemberPlusMemberModel;

abstract class EventsPlus extends Events
{
    /**
     * Current subevents
     *
     * @var array
     */
    protected $arrSubEvents = [];

    /**
     * Url cache
     *
     * @var array
     */
    protected static $arrUrlCache = [];

    protected $eventDetailCache = [];

    public function getPossibleFilterOptions($objModule)
    {
        \Controller::loadDataContainer('tl_calendar_events');

        $arrOptions = [];

        $arrFields = deserialize($objModule->formHybridEditable, true);

        // Return if there are no fields
        if (!is_array($arrFields) || empty($arrFields)) {
            return $arrOptions;
        }

        $strClass = \Module::findClass($objModule->type);

        if (class_exists($strClass)) {
            $objFilterModule = new $strClass($objModule);
            $arrOptions      = $objFilterModule->getFilterOptions();
        }

        return $arrOptions;
    }

    public function getFilter($objModule)
    {
        \Controller::loadDataContainer('tl_calendar_events');

        $arrFilter = [];

        $arrFields = deserialize($objModule->formHybridEditable, true);

        // Return if there are no fields
        if (!is_array($arrFields) || empty($arrFields)) {
            return $arrFilter;
        }

        $objHelper = new EventFilterHelper();

        $arrEventTypeArchives = deserialize($objModule->cal_eventTypesArchive, true);

        foreach ($arrFields as $strKey) {
            $arrData = $GLOBALS['TL_DCA']['tl_calendar_events']['fields'][$strKey];

            if (!is_array($arrData) || empty($arrData)) {
                continue;
            }

            $arrFilter[$strKey] = $objHelper->getValueByDca(\Input::get($strKey), $arrData);

            if (!$objModule->cal_combineEventTypesArchive && count($arrEventTypeArchives) > 0 && strrpos($strKey, 'eventtypes', -strlen($strKey)) !== false) {
                // unset eventtypes
                unset($arrFilter[$strKey]);

                // use multiple eventtypes
                foreach ($arrEventTypeArchives as $intArchive) {
                    $strArchiveKey             = $strKey . '_' . $intArchive;
                    $arrFilter[$strArchiveKey] = $objHelper->getValueByDca(\Input::get($strArchiveKey), $arrData);
                }
            }
        }

        return $arrFilter;
    }

    protected function prepareFilterModel($objModel)
    {
        // add keyword support
        if ($objModel->cal_addKeywordSearch) {
            $objModel->formHybridEditable = deserialize($objModel->formHybridEditable, true);
            $objModel->formHybridEditable = array_unique(array_merge($objModel->formHybridEditable, ['q']));
        }

        return $objModel;
    }

    /**
     * Get all events count of a certain period
     *
     * @param array
     * @param integer
     * @param integer
     *
     * @return integer
     */
    protected function getAllEventsCount($arrCalendars, $intStart, $intEnd, $arrFilter = [], $arrFilterOptions = [], $arrFilterConfig = [], $arrOptions = [])
    {
        if (!is_array($arrCalendars)) {
            return 0;
        }

        // set end date from filter
        if ($arrFilter['startDate'] && $arrFilter['startDate'] > $intStart) {
            $intStart = $arrFilter['startDate'];
        }

        // set end date from filter
        if ($arrFilter['endDate'] && $arrFilter['endDate'] <= $intEnd) {
            $intEnd = strtotime(date('d.m.Y', $arrFilter['endDate']) . ' 23:59:59'); // until last second of the day
        }

        return CalendarPlusEventsModel::countCurrentByPidAndFilter($arrCalendars, $intStart, $intEnd, $arrFilter, $arrFilterOptions, $arrFilterConfig, $arrOptions);
    }

    /**
     * Get all events of a certain period
     *
     * @param array
     * @param integer
     * @param integer
     *
     * @return array
     */
    protected function getAllEvents($arrCalendars, $intStart, $intEnd, $arrFilter = [], $arrFilterOptions = [], $arrFilterConfig = [], $arrOptions = [])
    {
        if (!is_array($arrCalendars)) {
            return [];
        }

        $this->arrEvents = [];

        // set end date from filter
        if ($arrFilter['startDate'] && $arrFilter['startDate'] > $intStart) {
            $intStart = $arrFilter['startDate'];
        }

        // set end date from filter6
        if ($arrFilter['endDate'] && $arrFilter['endDate'] <= $intEnd) {
            $intEnd = strtotime(date('d.m.Y', $arrFilter['endDate']) . ' 23:59:59'); // until last second of the day
        }

        // Get the events of the current period
        $objEvents = CalendarPlusEventsModel::findCurrentByPidAndFilter($arrCalendars, $intStart, $intEnd, $arrFilter, $arrFilterOptions, $arrFilterConfig, $arrOptions);

        if ($objEvents === null) {
            return [];
        }

        while ($objEvents->next()) {
            $this->addEvent($objEvents->current(), $objEvents->startTime, $objEvents->endTime, $intStart, $intEnd, $objEvents->pid);

            // Recurring events
            if ($objEvents->recurring) {
                $arrRepeat = deserialize($objEvents->repeatEach);

                if (!isset($arrRepeat['unit'], $arrRepeat['value']) || $arrRepeat['value'] < 1)
                {
                    continue;
                }

                $count        = 0;
                $intStartTime = $objEvents->startTime;
                $intEndTime   = $objEvents->endTime;
                $strtotime    = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

                while ($intEndTime < $intEnd) {
                    if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences) {
                        break;
                    }

                    $intStartTime = strtotime($strtotime, $intStartTime);
                    $intEndTime   = strtotime($strtotime, $intEndTime);

                    // Stop if the upper boundary is reached (see #8445)
                    if ($intStartTime === false || $intEndTime === false)
                    {
                        break;
                    }

                    // Skip events outside the scope
                    if ($intEndTime < $intStart || $intStartTime > $intEnd) {
                        continue;
                    }

                    $this->addEvent($objEvents->current(), $intStartTime, $intEndTime, $intStart, $intEnd, $objEvents->pid);

                }
            }
        }

        // Sort the array
        foreach (array_keys($this->arrEvents) as $key) {
            ksort($this->arrEvents[$key]);
        }

        // HOOK: modify the result set
        if (isset($GLOBALS['TL_HOOKS']['getAllEvents']) && is_array($GLOBALS['TL_HOOKS']['getAllEvents'])) {
            foreach ($GLOBALS['TL_HOOKS']['getAllEvents'] as $callback) {
                $this->import($callback[0]);
                $this->arrEvents = $this->{$callback[0]}->{$callback[1]}($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
            }
        }

        $arrIds = [];

        foreach ($this->arrEvents as $intKey => $arrDays) {
            foreach ($arrDays as $intStart => $arrEvents) {
                foreach ($arrEvents as $arrEvent) {
                    // do not add childevents to prev/next nav
                    if (!$this->cal_ungroupSubevents && $arrEvent['parentEvent'] > 0) {
                        continue;
                    }

                    $arrIds[] = $arrEvent['id'];
                }
            }
        }

        // store events ids in session
        $session                                 = \Session::getInstance()->getData();
        $session[CALENDARPLUS_SESSION_EVENT_IDS] = [];
        $session[CALENDARPLUS_SESSION_EVENT_IDS] = $arrIds;
        \Session::getInstance()->setData($session);

        return $this->arrEvents;
    }

    /**
     * Add an event to the array of active events
     *
     * @param object
     * @param integer
     * @param integer
     * @param string
     * @param integer
     * @param integer
     * @param integer
     */
    protected function addEvent($objEvents, $intStart, $intEnd, $intBegin, $intLimit, $intCalendar, $strUrl = null)
    {
        $span = \Calendar::calculateSpan($intStart, $intEnd);

        // Adjust the start time of a multi-day event (see #6802)
        if ($this->cal_noSpan && $span > 0 && $intStart < $intBegin && $intBegin < $intEnd) {
            $intStart = $intBegin;
        }

        $intDate = $intStart;
        $intKey  = date('Ymd', $intStart);

        $arrEvent = $this->getEventDetails($objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intCalendar);

        $this->arrEvents[$intKey][$intStart][] = $arrEvent;

        // Multi-day event
        for ($i = 1; $i <= $span && $intDate <= $intLimit; $i++) {
            // Only show first occurrence
            if ($this->cal_noSpan && $intDate >= $intBegin) {
                break;
            }

            $intDate    = strtotime('+ 1 day', $intDate);
            $intNextKey = date('Ymd', $intDate);

            $this->arrEvents[$intNextKey][$intDate][] = $arrEvent;
        }
    }

    protected function getEventDetails($objEvent, $intStart, $intEnd, $strUrl, $intBegin, $intCalendar)
    {
        global $objPage;
        $span = \Calendar::calculateSpan($intStart, $intEnd);

        // Adjust the start time of a multi-day event (see #6802)
        if ($this->cal_noSpan && $span > 0 && $intStart < $intBegin && $intBegin < $intEnd) {
            $intStart = $intBegin;
        }

        $strDate           = \Date::parse($objPage->dateFormat, $intStart);
        $strDay            = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
        $strMonth          = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart) - 1)];
        $strMemberTemplate = $this->mlTemplate;

        if ($span > 0) {
            $strDate = \Date::parse($objPage->dateFormat, $intStart) . ' - ' . \Date::parse($objPage->dateFormat, $intEnd);
            $strDay  = '';
        }

        $strTime = '';

        if ($objEvent->addTime) {
            if ($span > 0) {
                $strDate = \Date::parse($objPage->datimFormat, $intStart) . ' - ' . \Date::parse($objPage->datimFormat, $intEnd);
            } elseif ($intStart == $intEnd) {
                $strTime = \Date::parse($objPage->timeFormat, $intStart);
            } else {
                $strTime = \Date::parse($objPage->timeFormat, $intStart) . ' - ' . \Date::parse($objPage->timeFormat, $intEnd);
            }
        }

        // Store raw data
        $arrEvent = $objEvent->row();

        // Overwrite some settings
        $arrEvent['time']   = $strTime;
        $arrEvent['date']   = $strDate;
        $arrEvent['day']    = $strDay;
        $arrEvent['month']  = $strMonth;
        $arrEvent['parent'] = $intCalendar;
        $arrEvent['link']   = $objEvent->title;
        $arrEvent['target'] = '';
        $arrEvent['title']  = specialchars($objEvent->title, true);
        $arrEvent['href']   = $this->generateEventUrl($objEvent, $strUrl);

        if (($intParentEvent = $objEvent->parentEvent) > 0) {
            if (($objParentEvent = CalendarPlusEventsModel::findPublishedByParentAndIdOrAlias($intParentEvent, [$objEvent->pid])) !== null) {
                $arrEvent['parentHref'] = $this->generateEventUrl($objParentEvent, $strUrl);
                $arrEvent['isSubEvent'] = true;
            }
        }

        $arrEvent['class']              = ($objEvent->cssClass != '') ? ' ' . $objEvent->cssClass : '';
        $arrEvent['begin']              = $intStart;
        $arrEvent['end']                = $intEnd;
        $arrEvent['details']            = '';
        $arrEvent['startDateFormatted'] = $objEvent->startDate > 0 ? \Date::parse($objPage->dateFormat, $objEvent->startDate) : null;
        $arrEvent['endDateFormatted']   = $objEvent->endDate > 0 ? \Date::parse($objPage->dateFormat, $objEvent->endDate) : null;
        $arrEvent['startTimeFormated']  = $objEvent->startTime > 0 ? \Date::parse($objPage->timeFormat, $objEvent->startTime) : null;
        $arrEvent['endTimeFormated']    = $objEvent->endTime > 0 ? \Date::parse($objPage->timeFormat, $objEvent->endTime) : null;

        // modal
        if ($this->cal_showInModal && $objEvent->source == 'default' && $this->cal_readerModule) {
            $arrEvent['modal']       = true;
            $arrEvent['modalTarget'] = '#' . EventsPlusHelper::getCSSModalID($this->cal_readerModule);
        }

        if ($objEvent->promoter) {
            if (isset($this->eventDetailCache[$objEvent->id]['promoterList'])) {
                $arrEvent['promoterList'] = $this->eventDetailCache[$objEvent->id]['promoterList'];
            } else {
                $arrPromoters = deserialize($objEvent->promoter, true);

                if (!empty($arrPromoters)) {
                    $objPromoters = CalendarPromotersModel::findMultipleByIds($arrPromoters);

                    if ($objPromoters !== null) {
                        while ($objPromoters->next()) {
                            $objPromoter = $objPromoters->current();

                            if ($objPromoter->website != '') {
                                $strWebsiteLink = $objPromoter->website;

                                // Add http:// to the website
                                if (($strWebsiteLink != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsiteLink)) {
                                    $objPromoter->website = 'http://' . $strWebsiteLink;
                                }
                            }

                            $arrEvent['promoterList'][] = $objPromoter;
                        }
                    }
                }
            }
        }

        if ($objEvent->docents) {
            if (isset($this->eventDetailCache[$objEvent->id]['docentList'])) {
                $arrEvent['docentList'] = $this->eventDetailCache[$objEvent->id]['docentList'];
            } else {
                $objEvent->docents = deserialize($objEvent->docents, true);

                if (is_array($objEvent->docents) && !empty($objEvent->docents)) {
                    $objDocents = CalendarDocentsModel::findMultipleByIds($objEvent->docents);

                    if ($objDocents !== null) {
                        while ($objDocents->next()) {
                            $arrEvent['docentList'][] = $objDocents->current();
                        }
                    }
                }
            }
        }

        if ($objEvent->memberDocents) {
            if (isset($this->eventDetailCache[$objEvent->id]['memberDocentList'])) {
                $arrEvent['memberDocentList'] = $this->eventDetailCache[$objEvent->id]['memberDocentList'];
            } else {
                $objEvent->memberDocents = deserialize($objEvent->memberDocents, true);

                if (is_array($objEvent->memberDocents) && !empty($objEvent->memberDocents)) {
                    $objMembers = MemberPlusMemberModel::findMultipleByIds($objEvent->memberDocents);

                    if ($objMembers !== null) {
                        while ($objMembers->next()) {
                            $objMemberPlus = new \HeimrichHannot\MemberPlus\MemberPlus($this->objModel);
                            // custom subevent memberlist template
                            $objMemberPlus->mlTemplate      = ($arrEvent['isSubEvent'] && $this->cal_subeventDocentTemplate != '') ? $this->cal_subeventDocentTemplate : $objMemberPlus->mlTemplate;
                            $arrEvent['memberDocentList'][] = $objMemberPlus->parseMember($objMembers);
                        }
                    }
                }
            }
        }

        if ($objEvent->hosts) {
            if (isset($this->eventDetailCache[$objEvent->id]['hostList'])) {
                $arrEvent['hostList'] = $this->eventDetailCache[$objEvent->id]['hostList'];
            } else {
                $objEvent->hosts = deserialize($objEvent->hosts, true);

                if (is_array($objEvent->hosts) && !empty($objEvent->hosts)) {
                    $objDocents = CalendarDocentsModel::findMultipleByIds($objEvent->hosts);

                    if ($objDocents !== null) {
                        while ($objDocents->next()) {
                            $arrEvent['hostList'][] = $objDocents->current();
                        }
                    }
                }
            }
        }

        if ($objEvent->memberHosts) {
            if (isset($this->eventDetailCache[$objEvent->id]['memberHostList'])) {
                $arrEvent['memberHostList'] = $this->eventDetailCache[$objEvent->id]['memberHostList'];
            } else {
                $objEvent->memberHosts = deserialize($objEvent->memberHosts, true);

                if (is_array($objEvent->memberHosts) && !empty($objEvent->memberHosts)) {
                    $objMembers = MemberPlusMemberModel::findMultipleByIds($objEvent->memberHosts);

                    if ($objMembers !== null) {
                        while ($objMembers->next()) {
                            $objMemberPlus = new \HeimrichHannot\MemberPlus\MemberPlus($this->objModel);
                            // custom subevent memberlist template
                            $objMemberPlus->mlTemplate    = ($arrEvent['isSubEvent'] && $this->cal_subeventHostTemplate != '') ? $this->cal_subeventHostTemplate : $objMemberPlus->mlTemplate;
                            $arrEvent['memberHostList'][] = $objMemberPlus->parseMember($objMembers);
                        }
                    }

                }
            }
        }

        if ($objEvent->eventtypes) {
            if (isset($this->eventDetailCache[$objEvent->id]['eventtypeList'])) {
                $arrEvent['eventtypeList'] = $this->eventDetailCache[$objEvent->id]['eventtypeList'];
            } else {
                $objEvent->eventtypes = deserialize($objEvent->eventtypes, true);

                if (is_array($objEvent->eventtypes) && !empty($objEvent->eventtypes)) {
                    $objEventTypes = CalendarEventtypesModel::findMultipleByIds($objEvent->eventtypes);

                    if ($objEventTypes !== null) {
                        while ($objEventTypes->next()) {
                            $objEventtypesArchive = $objEventTypes->getRelated('pid');

                            if ($objEventtypesArchive === null) {
                                continue;
                            }

                            $strClass = (($objEventTypes->cssClass != '') ? ' ' . $objEventTypes->cssClass : '');
                            $strClass .= (($objEventtypesArchive->cssClass != '') ? ' ' . $objEventtypesArchive->cssClass : '');

                            $objEventTypes->class = $strClass;

                            $arrEvent['eventtypeList'][] = $objEventTypes->current();
                        }
                    }
                }
            }
        }

        if ($objEvent->rooms) {
            if (isset($this->eventDetailCache[$objEvent->id]['roomList'])) {
                $arrEvent['roomList'] = $this->eventDetailCache[$objEvent->id]['roomList'];
            } else {
                $objEvent->rooms = deserialize($objEvent->rooms, true);

                if (!empty($objEvent->rooms)) {
                    $objRooms = CalendarRoomModel::findPublishedByIds($objEvent->rooms);

                    if ($objRooms !== null) {
                        while ($objRooms->next()) {
                            $arrEvent['roomList'][] = $objRooms->current();
                        }
                    }

                }
            }
        }



        // time diff
        if ($objEvent->endTime > $objEvent->startTime) {
            $objDateStartTime = new \DateTime();
            $objDateStartTime->setTimestamp($objEvent->startTime);
            $objDateEndTime = new \DateTime();
            $objDateEndTime->setTimestamp($objEvent->endTime);
            $arrEvent['timeDiff'] = $objDateStartTime->diff($objDateEndTime);
        }


        if ($objEvent->website != '') {
            $arrEvent['websiteLink'] = $objEvent->website;

            // Add http:// to the website
            if (($objEvent->website != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $objEvent->website)) {
                $arrEvent['websiteLink'] = 'http://' . $objEvent->website;
            }
        }


        // Override the link target
        if ($objEvent->source == 'external' && $objEvent->target) {
            $arrEvent['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
        }

        // Clean the RTE output
        if ($arrEvent['teaser'] != '') {
            if ($objPage->outputFormat == 'xhtml') {
                $arrEvent['teaser'] = \StringUtil::toXhtml($arrEvent['teaser']);
            } else {
                $arrEvent['teaser'] = \StringUtil::toHtml5($arrEvent['teaser']);
            }
        }

        // Display the "read more" button for external/article links
        if ($objEvent->source != 'default') {
            $arrEvent['details'] = true;
        } // Compile the event text
        else {
            $objElement = \ContentModel::findPublishedByPidAndTable($objEvent->id, 'tl_calendar_events');

            if ($objElement !== null) {
                while ($objElement->next()) {
                    $arrEvent['details'] .= $this->getContentElement($objElement->current());
                }
            }
        }

        // Get todays start and end timestamp
        if ($this->intTodayBegin === null) {
            $this->intTodayBegin = strtotime('00:00:00');
        }
        if ($this->intTodayEnd === null) {
            $this->intTodayEnd = strtotime('23:59:59');
        }

        // Mark past and upcoming events (see #3692)
        if ($intEnd < $this->intTodayBegin) {
            $arrEvent['class'] .= ' bygone';
        } elseif ($intStart > $this->intTodayEnd) {
            $arrEvent['class'] .= ' upcoming';
        } else {
            $arrEvent['class'] .= ' current';
        }

        $this->eventDetailCache[$objEvent->id] = $arrEvent;
        return $arrEvent;
    }

    protected function addSingleEvent($objEvent, $strBegin)
    {
        $strUrl      = $this->strUrl;
        $objCalendar = \CalendarModel::findByPk($objEvent->pid);

        // Get the current "jumpTo" page
        if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null) {
            $strUrl = $this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/%s' : '/events/%s'));
        }

        return $this->getEventDetails($objEvent, $objEvent->startTime, $objEvent->endTime, $strUrl, $strBegin, $objEvent->pid);
    }


    protected function getParentEventDetails($intParentEvent, $intCalendar, $strBegin)
    {
        $objParentEvent = CalendarPlusEventsModel::findPublishedByParentAndIdOrAlias($intParentEvent, [$intCalendar]);

        // do not show subevent, if parent event does not exist
        if ($objParentEvent === null) {
            return null;
        }

        $strUrl      = $this->strUrl;
        $objCalendar = \CalendarModel::findByPk($objParentEvent->pid);

        // Get the current "jumpTo" page
        if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null) {
            $strUrl = $this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/%s' : '/events/%s'));
        }

        return $this->getEventDetails($objParentEvent, $objParentEvent->startTime, $objParentEvent->endTime, $strUrl, $strBegin, $objParentEvent->pid);
    }

    protected function addEventDetailsToTemplate($objTemplate, $event, $strClassList, $strClassUpcoming, $imgSize)
    {
        // Show the teaser text of redirect events (see #6315)
        if (is_bool($event['details'])) {
            $objTemplate->details = $event['teaser'];
        }

        // Add the template variables
        $objTemplate->classList     = $strClassList;
        $objTemplate->classUpcoming = $strClassUpcoming;
        $objTemplate->readMore      = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $event['title']));
        $objTemplate->more          = $GLOBALS['TL_LANG']['MSC']['more'];
        $objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];


        // Short view
        if ($this->cal_noSpan) {
            $objTemplate->day  = $event['day'];
            $objTemplate->date = $event['date'];
            $objTemplate->span = ($event['time'] == '' && $event['day'] == '') ? $event['date'] : '';
        } else {
            $objTemplate->day  = $event['firstDay'];
            $objTemplate->date = $event['firstDate'];
            $objTemplate->span = '';
        }

        $objTemplate->addImage = false;

        // Add an image
        if ($event['addImage'] && $event['singleSRC'] != '') {
            $objModel = \FilesModel::findByUuid($event['singleSRC']);

            if ($objModel === null) {
                if (!\Validator::isUuid($event['singleSRC'])) {
                    $objTemplate->text = '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                if ($imgSize) {
                    $event['size'] = $imgSize;
                }

                $event['singleSRC'] = $objModel->path;
                $this->addImageToTemplate($objTemplate, $event);
            }
        }

        $objTemplate->enclosure = [];

        // Add enclosure
        if ($event['addEnclosure']) {
            $this->addEnclosuresToTemplate($objTemplate, $event);
        }

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['addEventDetailsToTemplate']) && is_array($GLOBALS['TL_HOOKS']['addEventDetailsToTemplate'])) {
            foreach ($GLOBALS['TL_HOOKS']['addEventDetailsToTemplate'] as $callback) {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($objTemplate, $event, $this);
            }
        }
    }
}