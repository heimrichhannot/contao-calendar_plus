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
		$this->strMethod = FORMHYBRID_METHOD_GET;
		$this->isFilterForm = true;
		parent::__construct($objModule);
	}
	
	public function modifyDC(&$arrDca)
	{
		// adjust start date
		$arrDca['fields']['startDate']['eval']['mandatory'] = false;
		$arrDca['fields']['startDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['startDatePlaceholder'];
		$arrDca['fields']['startDate']['eval']['autocomplete'] = false;
		$arrDca['fields']['startDate']['eval']['linkedEnd'] = '#ctrl_endDate';
		$arrDca['fields']['startDate']['eval']['linkedUnlock'] = 'true';
		$arrDca['fields']['startDate']['eval']['data-toggle'] = 'tooltip';
		$arrDca['fields']['startDate']['eval']['minDate'] = \Date::parse(\Config::get('dateFormat'), time());

		// adjust end date
		$arrDca['fields']['endDate']['eval']['mandatory'] = false;
		$arrDca['fields']['endDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['endDatePlaceholder'];
		$arrDca['fields']['endDate']['eval']['autocomplete'] = false;
		$arrDca['fields']['endDate']['eval']['linkedStart'] = '#ctrl_startDate';
		$arrDca['fields']['endDate']['eval']['linkedUnlock'] = 'true';
		$arrDca['fields']['endDate']['eval']['data-toggle'] = 'tooltip';

		// adjust promoter field
		$arrDca['fields']['promoter']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getPromoterSelectOptions');
		unset($arrDca['fields']['promoter']['eval']['chosen']);
		$arrDca['fields']['promoter']['eval']['includeBlankOption'] = true;
		$arrDca['fields']['promoter']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['promoterBlankOptionLabel'];

		// adjust city field
		$arrDca['fields']['city']['inputType'] = 'select';
		$arrDca['fields']['city']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getCitySelectOptions');
		$arrDca['fields']['city']['eval']['includeBlankOption'] = true;
		$arrDca['fields']['city']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['cityBlankOptionLabel'];

		// adjust eventtypes field
		$arrDca['fields']['eventtypes']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getEventTypesFieldsByArchive');
		unset($arrDca['fields']['eventtypes']['eval']['chosen']);
		$arrDca['fields']['eventtypes']['eval']['multiple'] = false;
		$arrDca['fields']['eventtypes']['eval']['includeBlankOption'] = true;
		$arrDca['fields']['eventtypes']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['eventTypesBlankOptionLabel'];

		// adjust docents field

		if($this->objModule->cal_docent_combine)
		{
			$arrDca['fields']['docents']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getCombinedHostsAndDocentsSelectOptions');
		} else
		{
			$arrDca['fields']['docents']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getDocentSelectOptions');
		}

		unset($arrDca['fields']['docents']['eval']['chosen']);
		unset($arrDca['fields']['docents']['eval']['style']);
		$arrDca['fields']['docents']['eval']['multiple'] = false;
		$arrDca['fields']['docents']['eval']['includeBlankOption'] = true;
		$arrDca['fields']['docents']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['docentsBlankOptionLabel'];

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['loadDCEventFilterForm']) && is_array($GLOBALS['TL_HOOKS']['loadDCEventFilterForm']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadDCEventFilterForm'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($arrDca);
			}
		}

        if($this->cal_addKeywordSearch)
        {
            $this->addEditableField('q', $arrDca['fields']['q'], true);
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
