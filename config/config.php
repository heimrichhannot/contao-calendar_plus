<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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