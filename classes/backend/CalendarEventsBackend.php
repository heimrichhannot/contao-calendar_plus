<?php
/**
 * Created by PhpStorm.
 * User: jreinhardt
 * Date: 16.08.16
 * Time: 15:14
 */

namespace HeimrichHannot\CalendarPlus\Backend;


class CalendarEventsBackend extends \Backend
{

	public static function filterSubEvents(&$arrDca)
	{
		if (\Input::get('table') == 'tl_calendar_events') {
			$intEpid = \Input::get('epid');

			if ($intEpid) {
				if (($objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent($intEpid)) !== null) {
					while ($objEvents->next()) {
						$arrDca['list']['sorting']['root'][] = $objEvents->id;
					}
				} else {
					$arrDca['list']['sorting']['root'] = array(-1); // don't display anything
				}
			} else {
				if (($objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent(0)) !== null) {
					while ($objEvents->next()) {
						$arrDca['list']['sorting']['root'][] = $objEvents->id;
					}
				}
			}
		}
	}
}