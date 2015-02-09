<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_promoters';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_docents';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_eventtypes';

/**
 * Frontend modules
 */
array_insert($GLOBALS['FE_MOD']['events'], 2, array
	(
		'eventlist_plus' => 'HeimrichHannot\CalendarPlus\ModuleEventListPlus',
		'eventfilter'    => 'HeimrichHannot\CalendarPlus\ModuleEventFilter',
		'event_chooser'  => 'HeimrichHannot\CalendarPlus\ModuleEventChooser'
	)
);


$GLOBALS['FE_MOD']['events']['eventlist_plus'] = 'HeimrichHannot\CalendarPlus\ModuleEventListPlus';

/**
 * Hooks
 */
if (class_exists('HeimrichHannot\CalendarPlus\ExtendedEvents')) {
	$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('HeimrichHannot\CalendarPlus\ExtendedEvents', 'parseBackendTemplate');
	$GLOBALS['TL_HOOKS']['getAllEvents'][]         = array('HeimrichHannot\CalendarPlus\ExtendedEvents', 'getAllParentEvents');
}

/**
 * Form fields
 */
$GLOBALS['BE_FFL']['subEventList'] = 'HeimrichHannot\CalendarPlus\SubEventList';
$GLOBALS['TL_FFL']['subEventList'] = 'HeimrichHannot\CalendarPlus\SubEventList';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_calendar_promoters'] = 'HeimrichHannot\CalendarPlus\CalendarPromotersModel';
$GLOBALS['TL_MODELS']['tl_calendar_docents']   = 'HeimrichHannot\CalendarPlus\CalendarDocentsModel';
$GLOBALS['TL_MODELS']['tl_calendar_events']    = 'HeimrichHannot\CalendarPlus\CalendarPlusEventsModel';
$GLOBALS['TL_MODELS']['tl_calendar_eventtypes']    = 'HeimrichHannot\CalendarPlus\CalendarEventtypesModel';

/**
 * Constants
 */
define('CALENDARPLUS_SORTBY_DAY', 'day');

/**
 * EFG
 */
$GLOBALS['EFG']['storable_fields'][] = 'subEventList';
