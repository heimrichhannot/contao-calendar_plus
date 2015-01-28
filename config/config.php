<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_promoters';

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['events']['event_chooser'] = 'ModuleEventChooser';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('ExtendedEvents', 'parseBackendTemplate');
$GLOBALS['TL_HOOKS']['getAllEvents'][] = array('ExtendedEvents', 'getAllParentEvents');

/**
 * Form fields
 */

$GLOBALS['BE_FFL']['subEventList'] = 'SubEventList';