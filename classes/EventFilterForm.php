<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


class EventFilterForm extends \HeimrichHannot\FormHybrid\Form
{
	protected $strTable = 'tl_calendar_events';

	protected $strTemplate = 'formhybrid_eventfilter';

	public function __construct($objModule)
	{
		$this->strPalette = $objModule->formHybridPalette;
		$this->strMethod = FORMHYBRID_METHOD_GET;
		$this->isFilterForm = true;

		parent::__construct($objModule);
	}
	
	protected function modifyDC()
	{
		// adjust start date
		$this->dca['fields']['startDate']['eval']['mandatory'] = false;
		$this->dca['fields']['startDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['startDatePlaceholder'];
		$this->dca['fields']['startDate']['eval']['autocomplete'] = false;
		$this->dca['fields']['startDate']['eval']['linkedEnd'] = '#ctrl_endDate';
		$this->dca['fields']['startDate']['eval']['linkedUnlock'] = 'true';
		$this->dca['fields']['startDate']['eval']['data-toggle'] = 'tooltip';
		$this->dca['fields']['startDate']['eval']['minDate'] = \Date::parse(\Config::get('dateFormat'), time());

		// adjust end date
		$this->dca['fields']['endDate']['eval']['mandatory'] = false;
		$this->dca['fields']['endDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['endDatePlaceholder'];
		$this->dca['fields']['endDate']['eval']['autocomplete'] = false;
		$this->dca['fields']['endDate']['eval']['linkedStart'] = '#ctrl_startDate';
		$this->dca['fields']['endDate']['eval']['linkedUnlock'] = 'true';
		$this->dca['fields']['endDate']['eval']['data-toggle'] = 'tooltip';

		// adjust promoter field
		$this->dca['fields']['promoter']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getPromoterSelectOptions');
		unset($this->dca['fields']['promoter']['eval']['chosen']);
		$this->dca['fields']['promoter']['eval']['includeBlankOption'] = true;
		$this->dca['fields']['promoter']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['promoterBlankOptionLabel'];

		// adjust city field
		$this->dca['fields']['city']['inputType'] = 'select';
		$this->dca['fields']['city']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getCitySelectOptions');
		$this->dca['fields']['city']['eval']['includeBlankOption'] = true;
		$this->dca['fields']['city']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['cityBlankOptionLabel'];

		// adjust eventtypes field
		$this->dca['fields']['eventtypes']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getEventTypesFieldsByArchive');
		unset($this->dca['fields']['eventtypes']['eval']['chosen']);
		$this->dca['fields']['eventtypes']['eval']['multiple'] = false;
		$this->dca['fields']['eventtypes']['eval']['includeBlankOption'] = true;
		$this->dca['fields']['eventtypes']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['eventTypesBlankOptionLabel'];

		// adjust docents field
		$this->dca['fields']['docents']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getDocentSelectOptions');
		unset($this->dca['fields']['docents']['eval']['chosen']);
		unset($this->dca['fields']['docents']['eval']['style']);
		$this->dca['fields']['docents']['eval']['multiple'] = false;
		$this->dca['fields']['docents']['eval']['includeBlankOption'] = true;
		$this->dca['fields']['docents']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['docentsBlankOptionLabel'];

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['loadDCEventFilterForm']) && is_array($GLOBALS['TL_HOOKS']['loadDCEventFilterForm']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadDCEventFilterForm'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this->dca);
			}
		}

		return true;
	}
	
	protected function onSubmitCallback(\DataContainer $dc) {
		$this->submission = $dc;
	}

	protected function compile() {}

	protected function generateSubmitField()
	{
		$arrData = array
		(
			'inputType' => 'submit',
			'label'     => &$GLOBALS['TL_LANG']['eventfilter']['submit'],
			'eval'      => array('class' => 'btn btn-primary')
		);

		$this->arrFields[FORMHYBRID_NAME_SUBMIT] = $this->generateField(FORMHYBRID_NAME_SUBMIT, $arrData);
	}

	public function getFilterFields()
	{
		return $this->arrFields;
	}
}