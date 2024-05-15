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

class ExtendedEvents extends \Frontend
{

    public function parseBackendTemplate($strContent, $strTemplate)
    {
        if ('be_main' !== $strTemplate) {
            return $strContent;
        }

        if ($_GET['epid']) {
            $objEvent = CalendarPlusEventsModel::findByPk($_GET['epid']);
            if ($objEvent !== null) {
                if ($this->Input->get('table') == 'tl_content') {
                    $strContent = preg_replace(
                        '/<a href=\"(.*?)\" class=\"header_back\"/',
                        '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&epid=' . $_GET['epid'] . '&pid=' . $_GET['pid'] . '&id='.$objEvent->pid.'"',
                        $strContent
                    );
                } else if (\Input::get('act') && \Input::get('epid')) {
                    $strContent = preg_replace(
                        '/<a href=\"(.*?)\" class=\"header_back\"/',
                        '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&epid=' . $_GET['epid']. '&id='.$objEvent->pid.'&pid=' . $_GET['pid'] .'"',
                        $strContent
                    );
                } else {
                    $strContent = preg_replace(
                        '/<a href=\"(.*?)\" class=\"header_back\"/',
                        '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&id=' . $_GET['pid'] . '"',
                        $strContent
                    );
                }
            }
        }

        return $strContent;
    }

    public function getAllParentEvents($arrEvents, $arrCalendars, $intStart, $intEnd, \Module $objModule)
    {
        $arrResult = [];
        if ($objModule->hideSubEvents) {
            foreach ($arrEvents as $date => $eventsThisDay) {
                foreach ($eventsThisDay as $startDateTime => $eventsThisDateTime) {
                    foreach ($eventsThisDateTime as $currentEventThisDateTime) {
                        if (!$currentEventThisDateTime['parentEvent']) {
                            $arrResult[$date][$startDateTime][] = $currentEventThisDateTime;
                        }
                    }
                }
            }
        }

        return $objModule->hideSubEvents ? $arrResult : $arrEvents;
    }

}