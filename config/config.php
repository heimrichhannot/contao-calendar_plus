<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_promoters';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_docents';

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['events']['event_chooser'] = 'HeimrichHannot\CalendarPlus\ModuleEventChooser';

/**
 * Hooks
 */
if(class_exists('HeimrichHannot\CalendarPlus\ExtendedEvents'))
{
	$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('HeimrichHannot\CalendarPlus\ExtendedEvents', 'parseBackendTemplate');
	$GLOBALS['TL_HOOKS']['getAllEvents'][] = array('HeimrichHannot\CalendarPlus\ExtendedEvents', 'getAllParentEvents');
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
$GLOBALS['TL_MODELS']['tl_calendar_docents'] = 'HeimrichHannot\CalendarPlus\CalendarDocentsModel';
$GLOBALS['TL_MODELS']['tl_calendar_events'] = 'HeimrichHannot\CalendarPlus\CalendarPlusEventsModel';

