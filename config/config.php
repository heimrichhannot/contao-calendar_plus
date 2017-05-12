<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_promoters';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_docents';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_eventtypes_archive';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_eventtypes';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_room_archive';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_room';

/**
 * Frontend modules
 */
array_insert(
    $GLOBALS['FE_MOD']['events'],
    2,
    [
        'eventlist_plus'   => 'HeimrichHannot\CalendarPlus\ModuleEventListPlus',
        'eventreader_plus' => 'HeimrichHannot\CalendarPlus\ModuleEventReaderPlus',
        'eventfilter'      => 'HeimrichHannot\CalendarPlus\ModuleEventFilter',
        'event_chooser'    => 'HeimrichHannot\CalendarPlus\ModuleEventChooser',
    ]
);


$GLOBALS['FE_MOD']['events']['eventlist_plus'] = 'HeimrichHannot\CalendarPlus\ModuleEventListPlus';

/**
 * Hooks
 */
if (class_exists('HeimrichHannot\CalendarPlus\ExtendedEvents'))
{
    $GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = ['HeimrichHannot\CalendarPlus\ExtendedEvents', 'parseBackendTemplate'];
    $GLOBALS['TL_HOOKS']['getAllEvents'][]         = ['HeimrichHannot\CalendarPlus\ExtendedEvents', 'getAllParentEvents'];
}

/**
 * Form fields
 */
$GLOBALS['BE_FFL']['subEventList'] = 'HeimrichHannot\CalendarPlus\SubEventList';
$GLOBALS['TL_FFL']['subEventList'] = 'HeimrichHannot\CalendarPlus\SubEventList';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_calendar_promoters']          = 'HeimrichHannot\CalendarPlus\CalendarPromotersModel';
$GLOBALS['TL_MODELS']['tl_calendar_docents']            = 'HeimrichHannot\CalendarPlus\CalendarDocentsModel';
$GLOBALS['TL_MODELS']['tl_calendar_events']             = 'HeimrichHannot\CalendarPlus\CalendarPlusEventsModel';
$GLOBALS['TL_MODELS']['tl_calendar_eventtypes']         = 'HeimrichHannot\CalendarPlus\CalendarEventtypesModel';
$GLOBALS['TL_MODELS']['tl_calendar_eventtypes_archive'] = 'HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel';
$GLOBALS['TL_MODELS']['tl_calendar_room_archive']       = 'HeimrichHannot\CalendarPlus\CalendarRoomArchiveModel';
$GLOBALS['TL_MODELS']['tl_calendar_room']               = 'HeimrichHannot\CalendarPlus\CalendarRoomModel';

/**
 * Constants
 */
define('CALENDARPLUS_SORTBY_DAY', 'day');
define('CALENDARPLUS_SESSION_EVENT_IDS', 'CALENDAR_PLUS_EVENT_IDS');
define('CALENDARPLUS_FILTER', 'CALENDARPLUS_FILTER');
define('CALENDARPLUS_PROMOTER_TYPE_DEFAULT', 'default');

/**
 * EFG
 */
$GLOBALS['EFG']['storable_fields'][] = 'subEventList';

/**
 * Javascript
 */
/**
 * JS
 */
if (TL_MODE == 'FE')
{
    $GLOBALS['TL_JAVASCRIPT']['infinitescroll'] = '/system/modules/calendar_plus/assets/js/jscroll/jquery.jscroll.min.js';
    $GLOBALS['TL_JAVASCRIPT']['calendarplus']   =
        '/system/modules/calendar_plus/assets/js/jquery.calendarplus' . (!$GLOBALS['TL_CONFIG']['debugMode'] ? '.min' : '') . '.js|static';
}

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['calendar']['promoterlist'] = 'HeimrichHannot\CalendarPlus\ContentCalendarPromoterList';

/**
 * Modal module configuration
 */
$GLOBALS['MODAL_MODULES']['eventlist_plus'] = array
(
    'invokePalette' => 'cal_template', // The modal palette will be invoked after the field customTpl; as example
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['addEventDetailsToTemplate'][] = array('HeimrichHannot\CalendarPlus\Hooks', 'addEventDetailsToTemplate');