<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$dc = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */

$dc['palettes']['event_chooser'] = '{title_legend},name,headline,type;{redirect_legend},jumpTo';
$dc['palettes']['eventlist'] = str_replace('cal_noSpan', 'hideSubEvents,cal_noSpan', $dc['palettes']['eventlist']);

/**
 * Fields
 */

$dc['fields']['hideSubEvents'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hideSubEvents'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'sql'                     => "char(1) NOT NULL default ''"
);