<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_dav
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


class ModuleEventFilter extends EventsPlus
{
	/**
	 * Current date object
	 * @var integer
	 */
	protected $Date;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_eventfilter';

	public function __construct($objModule, $strColumn='main')
	{
		$objModule = $this->prepareFilterModel($objModule);
		parent::__construct($objModule, $strColumn);
	}

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['eventlist_plus'][0]) . ' ###';
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || empty($this->cal_calendar)) {
			return '';
		}

		return parent::generate();
	}

	protected function compile()
	{

		// needs to be overwritten in model, otherwise datacontainer argument in options_callback contains protected calendars
		$this->objModel->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));


		$objForm = new EventFilterForm($this->objModel);

		$this->Template->form = $objForm->generate();
	}

	public function getFilterOptions()
	{
		$arrOptions = array();

		// needs to be overwritten in model, otherwise datacontainer argument in options_callback contains protected calendars
		$this->objModel->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		$objForm = new EventFilterForm($this->objModel);
		$objForm->generate();

		$arrFields = $objForm->getFilterFields();

		if(!is_array($arrFields) || empty($arrFields)) return $arrOptions;

		$arrRestricedValueFields = deserialize($this->cal_restrictedValueFields, true);

		$arrEventTypeArchives  = deserialize($this->cal_eventTypesArchive, true);

		foreach($arrFields as $strName => $objWidget)
		{
			if(!is_array($objWidget->options)) continue;

			if(!in_array($strName, $arrRestricedValueFields)) continue;
			
			$arrFieldOptions = array();

			foreach($objWidget->options as $arrOption)
			{
				foreach ($arrOption as $strKey => $varValue)
				{
					// event types may be split into seperate lists
					if(is_array($varValue) && $varValue['value'] == 'options')
					{
						$arrFieldOptions = array_merge($arrFieldOptions, $varValue['label']);
					}
					
					else if($strKey == 'value' && $varValue != '')
					{
						$arrFieldOptions[] = $varValue;
					}
				}
			}

			if(!$this->cal_combineEventTypesArchive && count($arrEventTypeArchives) > 0 && strrpos($strName, 'eventtypes', -strlen($strName)) !== FALSE)
			{
				// use multiple eventtypes
				foreach($arrEventTypeArchives as $intArchive)
				{
					$strArchiveKey = $strName . '_' . $intArchive;
					$arrOptions[$strArchiveKey] = $arrFieldOptions;
				}
			}
			else
			{
				$arrOptions[$strName] = $arrFieldOptions;
			}
		}

		return $arrOptions;
	}

}