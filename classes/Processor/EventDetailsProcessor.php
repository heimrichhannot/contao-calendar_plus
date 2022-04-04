<?php

namespace HeimrichHannot\CalendarPlus\Processor;

use Contao\ModuleModel;
use Contao\StringUtil;
use HeimrichHannot\CalendarPlus\CalendarDocentsModel;
use HeimrichHannot\CalendarPlus\CalendarEventtypesModel;
use HeimrichHannot\CalendarPlus\CalendarPromotersModel;
use HeimrichHannot\CalendarPlus\CalendarRoomModel;
use HeimrichHannot\MemberPlus\MemberPlus;
use HeimrichHannot\MemberPlus\MemberPlusMemberModel;

class EventDetailsProcessor
{
    public static function promoterList(array $objEvent): ?array
    {
        $objEvent = (object)$objEvent;
        $result = null;

        $arrPromoters = StringUtil::deserialize($objEvent->promoter, true);

        if (!empty($arrPromoters)) {
            $objPromoters = CalendarPromotersModel::findMultipleByIds($arrPromoters);

            if ($objPromoters !== null) {
                $result = [];
                while ($objPromoters->next()) {
                    $objPromoter = $objPromoters->current();

                    if ($objPromoter->website != '') {
                        $strWebsiteLink = $objPromoter->website;

                        // Add http:// to the website
                        if (($strWebsiteLink != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsiteLink)) {
                            $objPromoter->website = 'http://' . $strWebsiteLink;
                        }
                    }

                    $result[] = $objPromoter;
                }
            }
        }
        return $result;
    }

    public static function docentList(array $event): ?array
    {
        $event = (object)$event;
        $docentList = null;
        if ($event->docents) {

            $docents = StringUtil::deserialize($event->docents, true);

            if (!empty($docents)) {
                $objDocents = CalendarDocentsModel::findMultipleByIds($docents);

                if ($objDocents !== null) {
                    $docentList = [];
                    while ($objDocents->next()) {
                        $docentList[] = $objDocents->current();
                    }
                }
            }
        }
        return $docentList;
    }

    public static function memberDocentList(array $event, ModuleModel $model): ?array
    {
        $event      = (object)$event;
        $docentList = null;

        $docents = StringUtil::deserialize($event->memberDocents, true);

        if (!empty($docents)) {
            $objDocents = MemberPlusMemberModel::findMultipleByIds($event->memberDocents);

            if ($objDocents !== null) {
                $docentList = [];
                while ($objDocents->next()) {
                    $objMemberPlus = new MemberPlus($model);
                    // custom subevent memberlist template
                    $objMemberPlus->mlTemplate = ($event->isSubEvent && $model->cal_subeventDocentTemplate != '') ? $model->cal_subeventDocentTemplate : $objMemberPlus->mlTemplate;
                    $docentList[]              = $objMemberPlus->parseMember($objDocents);
                }
            }
        }
        return $docentList;
    }

    public static function hostList(array $objEvent): ?array
    {
        $objEvent = (object)$objEvent;
        $result = null;

        $hosts = StringUtil::deserialize($objEvent->hosts, true);

        if (!empty($hosts)) {
            $objDocents = CalendarDocentsModel::findMultipleByIds($hosts);

            if ($objDocents !== null) {
                $result = [];
                while ($objDocents->next()) {
                    $result[] = $objDocents->current();
                }
            }
        }
        return $result;
    }

    public static function memberHostList(array $event, ModuleModel $model): ?array
    {
        $event      = (object)$event;
        $result = null;

        $member = StringUtil::deserialize($event->memberHosts, true);

        if (!empty($member)) {
            $memberHosts = MemberPlusMemberModel::findMultipleByIds($member);

            if ($memberHosts !== null) {
                $result = [];
                while ($memberHosts->next()) {
                    $objMemberPlus = new MemberPlus($model);
                    // custom subevent memberlist template
                    $objMemberPlus->mlTemplate = ($event->isSubEvent && $model->cal_subeventHostTemplate != '') ? $model->cal_subeventHostTemplate : $objMemberPlus->mlTemplate;
                    $result[]              = $objMemberPlus->parseMember($memberHosts);
                }
            }
        }

        return $result;
    }

    public static function roomList(array $objEvent): ?array
    {
        $objEvent = (object)$objEvent;
        $roomList = null;

        $rooms = StringUtil::deserialize($objEvent->rooms, true);

        if (!empty($rooms)) {
            $objRooms = CalendarRoomModel::findPublishedByIds($objEvent->rooms);

            if ($objRooms !== null) {
                $roomList = [];
                while ($objRooms->next()) {
                    $roomList[] = $objRooms->current();
                }
            }
        }
        return $roomList;
    }

    public static function eventTypeList(array $objEvent): ?array
    {
        $objEvent = (object)$objEvent;
        $eventtypeList = null;

        $eventtypes = StringUtil::deserialize($objEvent->eventtypes, true);

        if (!empty($eventtypes)) {
            $objEventTypes = CalendarEventtypesModel::findMultipleByIds($objEvent->eventtypes);

            if ($objEventTypes !== null) {
                $eventtypeList = [];
                while ($objEventTypes->next()) {
                    $objEventtypesArchive = $objEventTypes->getRelated('pid');

                    if ($objEventtypesArchive === null) {
                        continue;
                    }

                    $strClass = (($objEventTypes->cssClass != '') ? ' ' . $objEventTypes->cssClass : '');
                    $strClass .= (($objEventtypesArchive->cssClass != '') ? ' ' . $objEventtypesArchive->cssClass : '');

                    $objEventTypes->class = $strClass;

                    $eventtypeList[] = $objEventTypes->current();
                }
            }
        }
        return $eventtypeList;
    }
}