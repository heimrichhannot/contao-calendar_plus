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

		// Show the event reader if an item has been selected
		if ($this->cal_readerModule > 0 && (isset($_GET['events']) || (\Config::get('useAutoItem') && isset($_GET['auto_item'])))) {
			return $this->getFrontendModule($this->cal_readerModule, $this->strColumn);
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
}