<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_calendar_events'];

/**
 * Palettes
 */

$arrDca['palettes']['default'] = str_replace('title,', 'title,shortTitle,', $arrDca['palettes']['default']);
$arrDca['palettes']['default'] = str_replace(
	'endDate',
	'endDate,dates,parentEvent;{promoter_legend},promoter;{docents_legend},docents,memberDocents;{hosts_legend:hide},hosts,memberHosts;{eventtypes_legend},eventtypes',
	$arrDca['palettes']['default']
);
$arrDca['palettes']['default'] =
	str_replace('location', '{location_legend},street,postal,city,coordinates,location,locationAdditional,rooms', $arrDca['palettes']['default']);
$arrDca['palettes']['default'] = str_replace('{location_legend}', '{contact_legend},website;{location_legend}', $arrDca['palettes']['default']);




/**
 * Callbacks
 */

$arrDca['config']['onload_callback'][] = array('tl_calendar_events_plus', 'setDefaultParentEvent');
$arrDca['config']['onsubmit_callback'][] = array('tl_calendar_events_plus', 'clearCaches');

/**
 * Operations
 */
if (!isset($_GET['epid'])) {
	$arrDca['list']['operations']['showSubEvents'] = array
	(
		'button_callback' => array('tl_calendar_events_plus', 'showSubEvents'),
		'icon'            => '/system/modules/calendar_plus/assets/img/icons/show-sub-events.png'
	);
}

/**
 * Fields
 */
$arrFields = array(
	'shortTitle' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['shortTitle'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'parentEvent' => array
	(
		'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['parentEvent'],
		'inputType'  => 'select',
		'exclude'    => true,
		'foreignKey' => 'tl_calendar_events.title',
		'eval'       => array('tl_class' => 'long', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true),
		'wizard'     => array
		(
			array('tl_calendar_events_plus', 'editParentEvent')
		),
		'sql'        => "int(16) NOT NULL",
	),
	'locationAdditional' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['locationAdditional'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'street' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['street'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'postal' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['postal'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'city' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['city'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'coordinates' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['coordinates'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	'rooms' => array
	(
		'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['rooms'],
		'inputType'  => 'select',
		'foreignKey' => 'tl_calendar_room.title',
		'exclude'    => true,
		'options_callback' => array('tl_calendar_events_plus', 'getRooms'),
		'eval'       => array('chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'w50 clr'),
		'sql'        => "blob NULL" // ready for multiple
	),
	'promoter' => array
	(
		'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['promoter'],
		'inputType'  => 'select',
		'foreignKey' => 'tl_calendar_promoters.title',
		'exclude'    => true,
		'eval'       => array('chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'long', 'multiple' => true, 'style' => 'width: 853px'),
		'sql'        => "blob NULL"
	),
	'docents' => array
	(
		'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['docents'],
		'exclude'    => true,
		'search'     => true,
		'inputType'  => 'select',
		'foreignKey' => 'tl_calendar_docents.title',
		'eval'       => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
		'sql'        => "blob NULL",
		'relation'   => array('type' => 'hasMany', 'load' => 'lazy')
	),
	'memberDocents' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['memberDocents'],
		'exclude'          => true,
		'search'           => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_calendar_events_plus', 'getMemberDocents'),
		'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
		'sql'              => "blob NULL"
	),
	'hosts' => array
	(
		'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['hosts'],
		'exclude'    => true,
		'search'     => true,
		'inputType'  => 'select',
		'foreignKey' => 'tl_calendar_docents.title',
		'eval'       => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
		'sql'        => "blob NULL",
		'relation'   => array('type' => 'hasMany', 'load' => 'lazy')
	),
	'memberHosts' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['memberHosts'],
		'exclude'          => true,
		'search'           => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_calendar_events_plus', 'getMemberDocents'),
		'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
		'sql'              => "blob NULL"
	),
	'eventtypes' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventtypes'],
		'exclude'          => true,
		'search'           => true,
		'inputType'        => 'select',
		'options_callback' => array('tl_calendar_events_plus', 'getEventTypes'),
		'eval'             => array('multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
		'sql'              => "blob NULL"
	),
	'website' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['website'],
		'inputType' => 'text',
		'exclude'   => true,
		'eval'      => array('tl_class' => 'w50', 'rgxp' => 'url', 'maxlength' => 255),
		'sql'       => "varchar(255) NOT NULL default ''"
	),
	// keyword field
	'q' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['q'],
		'inputType' => 'text',
		'eval'      => array('placeholder' => &$GLOBALS['TL_LANG']['tl_calendar_events']['placeholders']['q'])
	),
	// dates field
	'dates' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['dates'],
		'inputType' => 'checkbox',
		'eval'      => array(
			'multiple' => true,
			'placeholder' => &$GLOBALS['TL_LANG']['tl_calendar_events']['placeholders']['dates'],
			'tl_class' => 'hidden'
		),
		'sql' => "char(1) NOT NULL default ''"
	)
);

$arrDca['fields'] += $arrFields;

$arrDca['fields']['location']['eval']['tl_class'] = 'w50 clr';

tl_calendar_events_plus::filterSubEvents($arrDca);

class tl_calendar_events_plus extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function editParentEvent(DataContainer $objDc)
	{
		return \HeimrichHannot\Haste\Dca\General::getModalEditLink('calendar', $objDc->value,
				$GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1], 'tl_calendar_events');
	}

	public function showSubEvents($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;pid=' . $row['pid'] . '&amp;epid=' . $row['id'] . '" title="'
			. specialchars($title) . '"' . $attributes . '>'
			. Image::getHtml($icon, $label) . '</a> ';
	}

	public function setDefaultParentEvent($objDc)
	{
		$intId = \Input::get('id');
		$intEventParentId = \Input::get('epid');

		if ($intId && $intEventParentId) {
			$objEvent       = \Contao\CalendarEventsModel::findByPk($intId);
			$objParentEvent = \Contao\CalendarEventsModel::findByPk($intEventParentId);

			if ($objEvent !== null && !$objEvent->parentEvent && $objParentEvent !== null) {
				if (!$objEvent->pid) {
					$objEvent->pid = $objParentEvent->pid;
				}

				$objEvent->parentEvent = $intEventParentId;
				$objEvent->save();
			}
		}
	}

	public function getRooms(\DataContainer $objDc)
	{
		$arrRooms = array();

		if (($objRoomArchives = \HeimrichHannot\CalendarPlus\CalendarRoomArchiveModel::findByPid($objDc->activeRecord->pid)) !== null)
		{
			foreach ($objRoomArchives as $objRoomArchive)
			{
				if (($objRooms = \HeimrichHannot\CalendarPlus\CalendarRoomModel::findByPid($objRoomArchive->id)) !== null)
				{
					foreach ($objRooms as $objRoom)
					{
						$arrRooms[$objRoomArchive->title][$objRoom->id] = $objRoom->title;
					}
				}
			}
		}

		return $arrRooms;
	}

	public function getMemberDocents(DataContainer $objDc)
	{
		$arrOptions = array();
		$objCalendar = \HeimrichHannot\CalendarPlus\CalendarPlusModel::findByPk($objDc->activeRecord->pid);

		if ($objCalendar === null)
		{
			return $arrOptions;
		}

		$arrMemberDocentGroups = deserialize($objCalendar->memberDocentGroups, true);

		if(!$objCalendar->addMemberDocentGroups || empty($arrMemberDocentGroups))
		{
			return $arrOptions;
		}

		$objMembers = \HeimrichHannot\MemberPlus\MemberPlusMemberModel::findActiveByGroups($arrMemberDocentGroups);

		if($objMembers === null)
		{
			return $arrOptions;
		}

		while($objMembers->next())
		{
			$arrTitle = array($objMembers->academicTitle, $objMembers->firstname, $objMembers->lastname);

			if (empty($arrTitle)) {
				continue;
			}

			$arrOptions[$objMembers->id] = implode(' ', $arrTitle);
		}

		return $arrOptions;
	}

	public function getEventTypes(DataContainer $objDc)
	{
		$arrOptions = array();

		$objCalendar = \HeimrichHannot\CalendarPlus\CalendarPlusModel::findByPk($objDc->activeRecord->pid);

		if ($objCalendar === null) {
			return $arrOptions;
		}

		// get additional archives from calendar config
		$arrArchiveIds = deserialize($objCalendar->eventTypeArchives, true);

		$objCurrentEventTypeArchives = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findBy('pid', $objDc->activeRecord->pid);
		
		if ($objCurrentEventTypeArchives !== null) {
			$arrArchiveIds = array_merge($arrArchiveIds, $objCurrentEventTypeArchives->fetchEach('id'));
		}

		$arrArchiveTitles     = array();
		$objEventTypeArchives = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findMultipleByIds($arrArchiveIds);

		if ($objEventTypeArchives !== null) {
			$arrArchiveTitles = $objEventTypeArchives->fetchEach('title');
		}

		$objEventTypes = \HeimrichHannot\CalendarPlus\CalendarEventtypesModel::findByPids($arrArchiveIds);

		if ($objEventTypes !== null) {
			while ($objEventTypes->next()) {
				$strGroup                                  = $arrArchiveTitles[$objEventTypes->pid];
				$arrOptions[$strGroup][$objEventTypes->id] = $objEventTypes->title;
			}
		}

		return $arrOptions;
	}

	public function clearCaches()
	{
		\HeimrichHannot\Haste\Cache\FileCache::getInstance()->clear();
	}

	public static function filterSubEvents(&$arrDca)
	{
		if (\Input::get('table') == 'tl_calendar_events') {
			$intEpid = \Input::get('epid');

			if ($intEpid) {
				if (($objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent($intEpid)) !== null) {
					while ($objEvents->next()) {
						$arrDca['list']['sorting']['root'][] = $objEvents->id;
					}
				} else {
					$arrDca['list']['sorting']['root'] = array(-1); // don't display anything
				}
			} else {
				if (($objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent(0)) !== null) {
					while ($objEvents->next()) {
						$arrDca['list']['sorting']['root'][] = $objEvents->id;
					}
				}
			}
		}
	}
}
