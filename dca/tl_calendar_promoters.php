<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package extended_events
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Table tl_calendar_promoters
 */
$GLOBALS['TL_DCA']['tl_calendar_promoters'] = array
(

	// Config
	'config'      => array
	(
		'dataContainer'    => 'Table',
		'ptable'           => 'tl_calendar',
		'switchToEdit'     => true,
		'enableVersioning' => true,
		'onload_callback'  => array
		(
			array('tl_calendar_promoters', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			'setDateAdded' => array('HeimrichHannot\\HastePlus\\Utilities', 'setDateAdded'),
		),
		'sql'              => array
		(
			'keys' => array
			(
				'id'  => 'primary',
				'pid' => 'index',
			),
		),
	),

	// List
	'list'        => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('title'),
			'headerFields'          => array('title', 'jumpTo', 'tstamp', 'protected', 'allowComments', 'makeFeed'),
			'panelLayout'           => 'filter;sort,search,limit',
			'child_record_callback' => array('tl_calendar_promoters', 'listPromoters'),
			'child_record_class'    => 'no_padding',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
			),
		),
		'operations'        => array
		(
			'edit'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif',
			),
			'copy'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['copy'],
				'href'  => 'act=paste&amp;mode=copy',
				'icon'  => 'copy.gif',
			),
			'cut'    => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['cut'],
				'href'  => 'act=paste&amp;mode=cut',
				'icon'  => 'cut.gif',
			),
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array('tl_calendar_promoters', 'toggleIcon'),
			),
			'show'   => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
			),
		),
	),

	// Palettes
	'palettes'    => array
	(
		'__selector__' => array('published'),
		'default'      => '{title_legend},title,alias;{teaser_legend},subtitle,teaser;{address_legend},company,street,postal,city,country,singleCoords;{contact_legend},contactName,phone,fax,email,website,room;{publish_legend},published',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'published' => 'start,stop',
	),

	// Fields
	'fields'      => array
	(
		'id'           => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'pid'          => array
		(
			'foreignKey' => 'tl_calendar.title',
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => array('type' => 'belongsTo', 'load' => 'eager'),
		),
		'tstamp'       => array
		(
			'sql' => "int(10) unsigned NOT NULL default '0'",
		),
		'dateAdded' => array
		(
			'label'   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
			'sorting' => true,
			'flag'    => 6,
			'eval'    => array('rgxp' => 'datim', 'doNotCopy' => true),
			'sql'     => "int(10) unsigned NOT NULL default '0'",
		),
		'title'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'alias'        => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('rgxp' => 'alias', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'),
			'save_callback' => array
			(
				array('tl_calendar_promoters', 'generateAlias'),
			),
			'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''",
		),
		'subtitle'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['subtitle'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 255, 'tl_class'=>'long'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'teaser'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['teaser'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'textarea',
			'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
			'sql'       => "text NULL",
		),
		'company'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['company'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'street'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['street'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'postal'       => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['postal'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 32, 'tl_class' => 'w50'),
			'sql'       => "varchar(32) NOT NULL default ''",
		),
		'city'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['city'],
			'exclude'   => true,
			'filter'    => true,
			'search'    => true,
			'sorting'   => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'country'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['country'],
			'exclude'   => true,
			'filter'    => true,
			'sorting'   => true,
			'inputType' => 'select',
			'options'   => System::getCountries(),
			'eval'      => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
			'sql'       => "varchar(2) NOT NULL default ''",
		),
		'singleCoords' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['singleCoords'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('maxlength' => 64, 'tl_class' => 'w50'),
			'sql'           => "varchar(64) NOT NULL default ''",
			'save_callback' => array
			(
				array('tl_calendar_promoters', 'generateSingleCoords'),
			),
		),
		'contactName'  => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['contactName'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array('maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'phone'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['phone'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'      => 64,
				'rgxp'           => 'phone',
				'decodeEntities' => true,
				'tl_class'       => 'w50',
			),
			'sql'       => "varchar(64) NOT NULL default ''",
		),
		'fax'          => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['fax'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'      => 64,
				'rgxp'           => 'phone',
				'decodeEntities' => true,
				'tl_class'       => 'w50',
			),
			'sql'       => "varchar(64) NOT NULL default ''",
		),
		'email'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['email'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'maxlength'      => 255,
				'rgxp'           => 'email',
				'decodeEntities' => true,
				'tl_class'       => 'w50',
			),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'website'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['website'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'text',
			'eval'      => array(
				'rgxp'      => 'url',
				'maxlength' => 255,
				'tl_class'  => 'w50',
			),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'room'         => array
		(
			'label'            => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['room'],
			'exclude'          => true,
			'filter'           => true,
			'sorting'          => true,
			'inputType'        => 'select',
			'options_callback' => array('tl_calendar_promoters', 'getAvailableRooms'),
			'eval'             => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
			'wizard'           => array
			(
				array('tl_calendar_promoters', 'editRoom'),
			),
			'sql'              => "int(10) unsigned NOT NULL default '0'",
		),
		'text'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['text'],
			'exclude'   => true,
			'search'    => true,
			'inputType' => 'textarea',
			'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
			'sql'       => "text NULL",
		),
		'published'    => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['published'],
			'exclude'   => true,
			'filter'    => true,
			'flag'      => 2,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true, 'doNotCopy' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'start'        => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['start'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
		'stop'         => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['stop'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
	),
);


/**
 * Class tl_calendar_promoters
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @package extended_events
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 **/
class tl_calendar_promoters extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_calendar_promoters
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin) {
			return;
		}

		// Set root IDs
		if (!is_array($this->User->calendars) || empty($this->User->calendars)) {
			$root = array(0);
		} else {
			$root = $this->User->calendars;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act')) {
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root)) {
					$this->log('Not enough permissions to create promoters in calendar ID "' . Input::get('pid') . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(Input::get('pid'), $root)) {
					$this->log('Not enough permissions to ' . Input::get('act') . ' promoters ID "' . $id . '" to calendar ID "' . Input::get('pid') . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
			// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
				$objPromoter = $this->Database->prepare("SELECT pid FROM tl_calendar_promoters WHERE id=?")
					->limit(1)
					->execute($id);

				if ($objPromoter->numRows < 1) {
					$this->log('Invalid event ID "' . $id . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objPromoter->pid, $root)) {
					$this->log('Not enough permissions to ' . Input::get('act') . ' promoters ID "' . $id . '" of calendar ID "' . $objCalendar->pid . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root)) {
					$this->log('Not enough permissions to access calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objPromoter = $this->Database->prepare("SELECT id FROM tl_calendar_promoters WHERE pid=?")
					->execute($id);

				if ($objPromoter->numRows < 1) {
					$this->log('Invalid calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session                   = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objPromoter->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act'))) {
					$this->log('Invalid command "' . Input::get('act') . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				} elseif (!in_array($id, $root)) {
					$this->log('Not enough permissions to access calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Auto-generate the event alias if it has not been set yet
	 *
	 * @param mixed
	 * @param \DataContainer
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '') {
			$autoAlias = true;
			$varValue  = standardize(String::restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_calendar_promoters WHERE alias=?")
			->execute($varValue);

		// Check whether the alias exists
		if ($objAlias->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias) {
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Add the type of input field
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function listPromoters($arrRow)
	{
		return '<div class="tl_content_left">' . $arrRow['title'] . '</div>';
	}


	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid'))) {
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_calendar_promoters::published', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (!$row['published']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
	}


	/**
	 * Disable/enable a promoter
	 *
	 * @param integer
	 * @param boolean
	 * @param \DataContainer
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->hasAccess('tl_calendar_promoters::published', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish promoters ID "' . $intId . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_calendar_promoters', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_calendar_promoters']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_calendar_promoters']['fields']['published']['save_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				} elseif (is_callable($callback)) {
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_calendar_promoters SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
			->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_calendar_promoters.id=' . $intId . '" has been created' . $this->getParentEntries('tl_calendar_promoters', $intId), __METHOD__, TL_GENERAL);

		// Update the RSS feed (for some reason it does not work without sleep(1))
		sleep(1);
		$this->import('Calendar');
		$this->Calendar->generateFeedsByCalendar(CURRENT_ID);
	}

	/**
	 *
	 * Get geo coodinates for the address
	 *
	 * @param               $varValue
	 * @param DataContainer $dc
	 *
	 * @return String The coordinates
	 */
	function generateSingleCoords($varValue, DataContainer $dc)
	{
		if ($varValue != '') {
			return $varValue;
		}

		$strAddress = '';

		if ($dc->activeRecord->street != '') {
			$strAddress .= $dc->activeRecord->street;
		}

		if ($dc->activeRecord->postal != '' && $dc->activeRecord->city) {
			$strAddress .= ($strAddress ? ',' : '') . $dc->activeRecord->postal . ' ' . $dc->activeRecord->city;
		}

		if (($strCoords = $this->generateCoordsFromAddress($strAddress, $dc->activeRecord->country ?: 'de')) !== false) {
			$varValue = $strCoords;
		}

		return $varValue;
	}


	/**
	 * @param $strAddress Address string
	 * @param $strCountry Country ISO 3166 code
	 *
	 * @return bool|string False if dlh_geocode is not installed, otherwise return the coordinates from address string
	 */
	private function generateCoordsFromAddress($strAddress, $strCountry)
	{
		if (!in_array('dlh_geocode', \ModuleLoader::getActive())) {
			return false;
		}

		return \delahaye\GeoCode::getCoordinates($strAddress, $strCountry, 'de');
	}

	/**
	 * Get all available rooms for this calendar
	 */
	public function getAvailableRooms(\DataContainer $dc)
	{
		$arrOptions = array();

		if (($objRooms = \HeimrichHannot\CalendarPlus\CalendarRoomModel::findAllByCalendar($dc->activeRecord->pid)) === null) {
			return $arrOptions;
		}

		while ($objRooms->next()) {
			$objRoomArchive = $objRooms->getRelated('pid');

			if ($objRoomArchive === null) {
				continue;
			}

			$arrOptions[$objRoomArchive->title][$objRooms->id] = $objRooms->title;
		}

		return $arrOptions;
	}

	/**
	 * Return the edit room wizard
	 *
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function editRoom(DataContainer $dc)
	{
		return ($dc->value < 1)
			? ''
			: ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_room&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(
				specialchars($GLOBALS['TL_LANG']['tl_calendar_promoters']['editroom'][1]),
				$dc->value
			) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(
				str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_calendar_promoters']['editroom'][1], $dc->value))
			) . '\',\'url\':this.href});return false">' . Image::getHtml(
				'alias.gif',
				$GLOBALS['TL_LANG']['tl_calendar_promoters']['editroom'][0],
				'style="vertical-align:top"'
			) . '</a>';
	}
}