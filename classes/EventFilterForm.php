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

	protected $strTemplate = 'form_eventfilter';

	public function __construct($objModule)
	{
		$this->strPalette = $objModule->formHybridPalette;
		$this->strMethod = FORMHYBRID_METHOD_GET;

		parent::__construct($objModule);
	}

	public function generate()
	{
		\System::loadLanguageFile($this->strTable);
		\DataContainer::loadDataContainer($this->strTable);
		$dc = &$GLOBALS['TL_DCA'][$this->strTable];

		// adjust start date
		$dc['fields']['startDate']['eval']['mandatory'] = false;
		$dc['fields']['startDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['startDatePlaceholder'];
		$dc['fields']['startDate']['eval']['autocomplete'] = false;

		// adjust end date
		$dc['fields']['endDate']['eval']['mandatory'] = false;
		$dc['fields']['endDate']['eval']['placeholder'] = &$GLOBALS['TL_LANG']['eventfilter']['endDatePlaceholder'];
		$dc['fields']['endDate']['eval']['autocomplete'] = false;

		// adjust city field
		$dc['fields']['promoter']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getPromoterSelectOptions');
		unset($dc['fields']['promoter']['eval']['chosen']);
		$dc['fields']['promoter']['eval']['includeBlankOption'] = true;
		$dc['fields']['promoter']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['promoterBlankOptionLabel'];

		// adjust city field
		$dc['fields']['city']['inputType'] = 'select';
		$dc['fields']['city']['options_callback'] = array('HeimrichHannot\CalendarPlus\EventFilterHelper', 'getCitySelectOptions');
		$dc['fields']['city']['eval']['includeBlankOption'] = true;
		$dc['fields']['city']['eval']['blankOptionLabel'] = &$GLOBALS['TL_LANG']['eventfilter']['cityBlankOptionLabel'];

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateEventFilterForm']) && is_array($GLOBALS['TL_HOOKS']['generateEventFilterForm']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateEventFilterForm'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($dc, $this);
			}
		}

		return parent::generate();
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
			'label'		=> &$GLOBALS['TL_LANG']['eventfilter']['submit'],
			'eval'		=> array('class' => 'btn btn-primary')
		);

		$this->arrFields[FORMHYBRID_NAME_SUBMIT] = $this->generateField(FORMHYBRID_NAME_SUBMIT, $arrData);
	}
}