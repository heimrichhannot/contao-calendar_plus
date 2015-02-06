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
									{config_legend},cal_calendar,cal_noSpan,cal_format,cal_ignoreDynamic,cal_order,cal_readerModule,cal_filterModule,cal_limit,perPage;
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
	'cal_filterModule'   => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_filterModule'],
		'exclude'   => true,
		'inputType' => 'select',
		'options_callback'        => array('tl_module_calendar_plus', 'getFilterModules'),
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		'sql'                     => "int(10) unsigned NOT NULL default '0'"
	),
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);

class tl_module_calendar_plus extends \Backend
{
	/**
	 * Get all event filter modules and return them as array
	 * @return array
	 */
	public function getFilterModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='eventfilter' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}
}