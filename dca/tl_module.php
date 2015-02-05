<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */

$dc['palettes']['event_chooser'] = '{title_legend},name,headline,type;{redirect_legend},jumpTo';
$dc['palettes']['eventlist']     = str_replace('cal_noSpan', 'hideSubEvents,cal_noSpan', $dc['palettes']['eventlist']);

$dc['palettes']['eventlist_plus'] = '
									{title_legend},name,headline,type;
									{config_legend},cal_calendar,cal_noSpan,cal_format,cal_ignoreDynamic,cal_order,cal_groupBy,cal_readerModule,cal_limit,perPage;
									{template_legend:hide},cal_template,customTpl;
									{image_legend:hide},imgSize;
									{protected_legend:hide},protected;
									{expert_legend:hide},guests,cssID,space';

$dc['palettes']['eventfilter'] = '
									{title_legend},name,headline,type;
									{config_legend},cal_calendar,formHybridDataContainer,formHybridPalette,formHybridEditable,formHybridEditableSkip,formHybridAddDefaultValues;
									{template_legend:hide},customTpl;
									{protected_legend:hide},protected;
									{expert_legend:hide},guests,cssID,space';

/**
 * Fields
 */
$arrFields = array
(
	'hideSubEvents' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['hideSubEvents'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'sql'       => "char(1) NOT NULL default ''"
	),
	'cal_groupBy'   => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_groupBy'],
		'exclude'   => true,
		'inputType' => 'select',
		'options'   => array(CALENDARPLUS_SORTBY_DAY),
		'eval'      => array('tl_class' => 'w50 wizard', 'chosen' => true, 'includeBlankOption' => true),
		'reference' => &$GLOBALS['TL_LANG']['MSC'],
		'sql'       => "varchar(32) NOT NULL default ''"
	)
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);