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

$dc = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$dc['palettes']['__selector__'][] = 'cal_addKeywordSearch';


$dc['palettes']['event_chooser'] = '{title_legend},name,headline,type;{redirect_legend},jumpTo';
$dc['palettes']['eventlist']     = str_replace('cal_noSpan', 'hideSubEvents,cal_noSpan', $dc['palettes']['eventlist']);

$dc['palettes']['eventlist_plus'] = '
									{title_legend},name,headline,type;
									{config_legend},cal_calendar,cal_noSpan,cal_format,cal_ignoreDynamic,cal_order,cal_readerModule,cal_filterModule,cal_limit,perPage,cal_ungroupSubevents;
									{template_legend:hide},cal_template,cal_templateSubevent,customTpl,cal_showInModal;
									{image_legend:hide},imgSize;
									{protected_legend:hide},protected;
									{expert_legend:hide},guests,cssID,space';

$dc['palettes']['eventreader_plus'] = '
										{title_legend},name,headline,type;
										{config_legend},cal_calendar;
										{template_legend:hide},cal_template,cal_template_modal,cal_templateSubevent,customTpl;
										{image_legend},imgSize;{protected_legend:hide},protected;
										{memberdocent_legend},mlTemplate,mlLoadContent,mlImgSize,mlDisableImages,cal_subeventDocentTemplate,cal_subeventHostTemplate;
										{share_legend},addShare;
										{expert_legend:hide},guests,cssID,space';

$dc['palettes']['eventfilter'] = '
									{title_legend},name,headline,type;
									{config_legend},cal_calendar,formHybridDataContainer,formHybridPalette,formHybridEditable,formHybridEditableSkip,formHybridAddDefaultValues,formHybridTemplate;
									{keyword_legend},cal_addKeywordSearch;
									{docent_legend},cal_docent_combine;
									{eventtype_legend},cal_eventTypesArchive,cal_eventTypesArchiveMultiple,cal_combineEventTypesArchive,cal_combineEventTypesArchiveMultiple;
									{related_legend},cal_filterRelatedOnEmpty;
									{restrict_legend},cal_restrictedValueFields;
									{template_legend:hide},customTpl;
									{protected_legend:hide},protected;
									{expert_legend:hide},guests,cssID,space';


/**
 * Subpalettes
 */
$dc['subpalettes']['cal_addKeywordSearch'] = 'queryType,fuzzy';

/**
 * Fields
 */
$arrFields = array
(
	'hideSubEvents'                        => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['hideSubEvents'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'sql'       => "char(1) NOT NULL default ''"
	),
	'cal_filterModule'                     => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_filterModule'],
		'exclude'          => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_module_calendar_plus', 'getFilterModules'),
		'reference'        => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'             => array('includeBlankOption' => true, 'tl_class' => 'w50'),
		'sql'              => "int(10) unsigned NOT NULL default '0'"
	),
	'cal_template_modal'                   => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_template_modal'],
		'exclude'          => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_module_calendar_plus', 'getEventModalTemplates'),
		'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true),
		'sql'              => "varchar(64) NOT NULL default ''"
	),
	'cal_showInModal'                      => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_showInModal'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_eventTypesArchive'                => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchive'],
		'exclude'          => true,
		'inputType'        => 'checkboxWizard',
		'options_callback' => array('tl_module_calendar_plus', 'getEventTypesArchive'),
		'eval'             => array('tl_class' => 'w50', 'multiple' => true),
		'sql'              => "blob NULL"
	),
	'cal_combineEventTypesArchive'         => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchive'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_combineEventTypesArchiveMultiple' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchiveMultiple'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_eventTypesArchiveMultiple'        => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchiveMultiple'],
		'exclude'          => true,
		'inputType'        => 'checkbox',
		'options_callback' => array('tl_module_calendar_plus', 'getEventTypesArchive'),
		'eval'             => array('tl_class' => 'w50', 'multiple' => true),
		'sql'              => "blob NULL"
	),
	'cal_ungroupSubevents'                 => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_ungroupSubevents'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_templateSubevent'                 => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_templateSubevent'],
		'exclude'          => true,
		'inputType'        => 'select',
		'default'          => 'event_subevent',
		'options_callback' => array('tl_module_calendar_plus', 'getSubeventTemplates'),
		'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true),
		'sql'              => "varchar(64) NOT NULL default ''"
	),
	'cal_restrictedValueFields'            => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_restrictedValueFields'],
		'inputType'        => 'checkboxWizard',
		'options_callback' => array('tl_form_hybrid_module', 'getFields'),
		'exclude'          => true,
		'eval'             => array('multiple' => true, 'includeBlankOption' => true, 'tl_class' => 'w50 autoheight clr'),
		'sql'              => "blob NULL"
	),
	'cal_subeventDocentTemplate'                   => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_subeventDocentTemplate'],
		'exclude'          => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_module_member_plus', 'getMemberlistTemplates'),
		'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true),
		'sql'              => "varchar(64) NOT NULL default ''"
	),
	'cal_subeventHostTemplate'                     => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_subeventHostTemplate'],
		'exclude'          => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_module_member_plus', 'getMemberlistTemplates'),
		'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true),
		'sql'              => "varchar(64) NOT NULL default ''"
	),
	'cal_filterRelatedOnEmpty' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_filterRelatedOnEmpty'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'clr w50'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_addKeywordSearch' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_addKeywordSearch'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'clr w50', 'submitOnChange' => true),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'cal_docent_combine' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['cal_docent_combine'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('tl_class' => 'clr w50'),
		'sql'       => "char(1) NOT NULL default ''",
	)
);


$dc['fields'] = array_merge($dc['fields'], $arrFields);

$dc['fields']['cal_readerModule']['options_callback']   = array('tl_module_calendar_plus', 'getReaderModules');
$dc['fields']['cal_calendar']['eval']['submitOnChange'] = true;

class tl_module_calendar_plus extends \Backend
{
	/**
	 * Get all event filter modules and return them as array
	 *
	 * @return array
	 */
	public function getFilterModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute(
			"SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='eventfilter' ORDER BY t.name, m.name"
		);

		while ($objModules->next()) {
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Return all event modal templates as array
	 *
	 * @return array
	 */
	public function getEventModalTemplates()
	{
		return $this->getTemplateGroup('eventmodal_');
	}

	/**
	 * Return all event modal templates as array
	 *
	 * @return array
	 */
	public function getSubeventTemplates()
	{
		return $this->getTemplateGroup('event_');
	}

	/**
	 * Get all event reader modules and return them as array
	 *
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute(
			"SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type LIKE 'eventreader%' ORDER BY t.name, m.name"
		);

		while ($objModules->next()) {
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Get all eventtypesarchives and return them as array
	 *
	 * @return array
	 */
	public function getEventTypesArchive($dc)
	{
		$arrOptions = array();

		$arrCalendars = deserialize($dc->activeRecord->cal_calendar, true);
		$objArchives  = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findByPids($arrCalendars);

		if ($objArchives === null) {
			return $arrOptions;
		}

		return $objArchives->fetchEach('title');
	}
}