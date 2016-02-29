<?php

$GLOBALS['TL_DCA']['tl_calendar_room_archive'] = array
(
	'config'      => array
	(
		'dataContainer'     => 'Table',
		'ptable'            => 'tl_calendar',
		'ctable'            => array('tl_calendar_room'),
		'enableVersioning'  => true,
		'onload_callback' => array
		(
			array('tl_calendar_room_archive', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			'setDateAdded' => array('HeimrichHannot\\HastePlus\\Utilities', 'setDateAdded'),
		),
		'sql'               => array
		(
			'keys' => array
			(
				'id' => 'primary',
			),
		),
	),
	'list'        => array
	(
		'sorting'           => array
		(
			'mode'                  => 4,
			'fields'                => array('title'),
			'headerFields'          => array('title', 'jumpTo', 'tstamp', 'protected', 'allowComments', 'makeFeed'),
			'panelLayout'           => 'filter;sort,search,limit',
			'child_record_callback' => array('tl_calendar_room_archive', 'listRoomArchives'),
			'child_record_class'    => 'no_padding',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();"',
			),
		),
		'operations'        => array
		(
			'edit'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['edit'],
				'href'  => 'table=tl_calendar_room',
				'icon'  => 'edit.gif',
			),
			'editheader' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['editheader'],
				'href'  => 'act=edit',
				'icon'  => 'header.gif',
			),
			'copy'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['copy'],
				'href'  => 'act=copy',
				'icon'  => 'copy.gif',
			),
			'delete'     => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle'     => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['toggle'],
				'icon'            => 'visible.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' => array('tl_calendar_room_archive', 'toggleIcon'),
			),
			'show'       => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif',
			),
		),
	),
	'palettes'    => array
	(
		'__selector__' => array('published'),
		'default'      => '{title_legend},title;{publish_legend},published',
	),
	'subpalettes' => array
	(
		'published' => 'start,stop',
	),
	'fields'      => array
	(
		'id'        => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment",
		),
		'pid'       => array
		(
			'foreignKey' => 'tl_calendar.title',
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => array('type' => 'belongsTo', 'load' => 'eager'),
		),
		'tstamp'    => array
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
		'title'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['title'],
			'exclude'   => true,
			'search'    => true,
			'sorting'   => true,
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('mandatory' => true, 'maxlength' => 255),
			'sql'       => "varchar(255) NOT NULL default ''",
		),
		'published' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['published'],
			'exclude'   => true,
			'filter'    => true,
			'flag'      => 2,
			'inputType' => 'checkbox',
			'eval'      => array('submitOnChange' => true, 'doNotCopy' => true),
			'sql'       => "char(1) NOT NULL default ''",
		),
		'start'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['start'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
		'stop'      => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room_archive']['stop'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
			'sql'       => "varchar(10) NOT NULL default ''",
		),
	),
);


class tl_calendar_room_archive extends \Backend
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
	 * Check permissions to edit table tl_calendar_eventtypes_archive
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->calendars) || empty($this->User->calendars))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to create rooms in calendar ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' calendar room ID "'.$id.'" to calendar ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
			// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
				$objRoomArchive = $this->Database->prepare("SELECT pid FROM tl_calendar_room_archive WHERE id=?")
					->limit(1)
					->execute($id);

				if ($objRoomArchive->numRows < 1)
				{
					$this->log('Invalid room ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objRoomArchive->pid, $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' room-achives ID "'.$id.'" of calendar ID "'.$objRoomArchive->pid.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objRoomArchive = $this->Database->prepare("SELECT id FROM tl_calendar_room_archive WHERE pid=?")
					->execute($id);

				if ($objRoomArchive->numRows < 1)
				{
					$this->log('Invalid calendar ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objRoomArchive->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Invalid command "'.Input::get('act').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	public static function listRecords($arrRow)
	{
		return sprintf(
			'<div class="tl_content_left">%s</div>',
			$arrRow['title']
		);
	}

	/**
	 * Add the type of input field
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function listRoomArchives($arrRow)
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
		if (!$this->User->hasAccess('tl_calendar_room_archive::published', 'alexf')) {
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
		if (!$this->User->hasAccess('tl_calendar_room_archive::published', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish room ID "' . $intId . '"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_calendar_room_archive', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_calendar_room_archive']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_calendar_room_archive']['fields']['published']['save_callback'] as $callback) {
				if (is_array($callback)) {
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				} elseif (is_callable($callback)) {
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_calendar_room_archive SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
			->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_calendar_room_archive.id=' . $intId . '" has been created' . $this->getParentEntries('tl_calendar_room_archive', $intId), __METHOD__, TL_GENERAL);

		// Update the RSS feed (for some reason it does not work without sleep(1))
		sleep(1);
		$this->import('Calendar');
		$this->Calendar->generateFeedsByCalendar(CURRENT_ID);
	}

}
