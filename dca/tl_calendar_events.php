<?php

use HeimrichHannot\CalendarPlus\CalendarPlusModel;

$arrDca = &$GLOBALS['TL_DCA']['tl_calendar_events'];

$arrDca['config']['sql']['keys']['pid,start,stop,published,startTime,endTime'] = 'index';
$arrDca['config']['sql']['keys']['pid,recurring,recurrences,repeatEnd']        = 'index';


/**
 * Palettes
 */

$arrDca['palettes']['default'] = str_replace('title,', 'title,shortTitle,', $arrDca['palettes']['default']);
$arrDca['palettes']['default'] = str_replace('endDate', 'endDate,dates,parentEvent;{promoter_legend},promoter;{linkedMembers_legend:hide},linkedMembers;{docents_legend},docents,memberDocents;{hosts_legend:hide},hosts,memberHosts;{eventtypes_legend},eventtypes', $arrDca['palettes']['default']);
$arrDca['palettes']['default'] = str_replace('location', '{location_legend},street,postal,city,coordinates,location,locationAdditional,rooms', $arrDca['palettes']['default']);
$arrDca['palettes']['default'] = str_replace('{location_legend}', '{contact_legend},website;{location_legend}', $arrDca['palettes']['default']);


/**
 * Callbacks
 */

$arrDca['config']['onload_callback'][]   = ['tl_calendar_events_plus', 'setDefaultParentEvent'];
$arrDca['config']['onload_callback'][]   = ['tl_calendar_events_plus', 'filterSubEvents'];
$arrDca['config']['onsubmit_callback'][] = ['tl_calendar_events_plus', 'clearCaches'];

/**
 * Operations
 */
if (!isset($_GET['epid'])) {
    $arrDca['list']['operations']['showSubEvents'] = [
        'button_callback' => ['tl_calendar_events_plus', 'showSubEvents'],
        'icon'            => '/system/modules/calendar_plus/assets/img/icons/show-sub-events.png',
    ];
}

/**
 * Fields
 */
$arrFields = [
    'shortTitle'         => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['shortTitle'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'parentEvent'        => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['parentEvent'],
        'inputType'        => 'select',
        'exclude'          => true,
        'options_callback' => ['tl_calendar_events_plus', 'getParentEventChoices'],
        'eval'             => ['tl_class' => 'long clr', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true],
        'wizard'           => [
            ['tl_calendar_events_plus', 'editParentEvent'],
        ],
        'sql'              => "int(16) NOT NULL default '0'",
    ],
    'locationAdditional' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['locationAdditional'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'street'             => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['street'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'postal'             => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['postal'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'city'               => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['city'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'coordinates'        => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['coordinates'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    'rooms'              => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['rooms'],
        'inputType'        => 'select',
        'foreignKey'       => 'tl_calendar_room.title',
        'exclude'          => true,
        'options_callback' => ['tl_calendar_events_plus', 'getRooms'],
        'eval'             => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'w50 clr'],
        'sql'              => "blob NULL" // ready for multiple
    ],
    'promoter'           => [
        'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['promoter'],
        'inputType'  => 'select',
        'foreignKey' => 'tl_calendar_promoters.title',
        'exclude'    => true,
        'eval'       => ['chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'long', 'multiple' => true, 'style' => 'width: 853px'],
        'sql'        => "blob NULL",
    ],
    'docents'            => [
        'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['docents'],
        'exclude'    => true,
        'search'     => true,
        'inputType'  => 'select',
        'foreignKey' => 'tl_calendar_docents.title',
        'eval'       => ['multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'],
        'sql'        => "blob NULL",
        'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
    ],
    'memberDocents'      => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['memberDocents'],
        'exclude'          => true,
        'search'           => true,
        'inputType'        => 'select',
        'options_callback' => ['tl_calendar_events_plus', 'getMemberDocents'],
        'eval'             => ['multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'],
        'sql'              => "blob NULL",
    ],
    'hosts'              => [
        'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['hosts'],
        'exclude'    => true,
        'search'     => true,
        'inputType'  => 'select',
        'foreignKey' => 'tl_calendar_docents.title',
        'eval'       => ['multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'],
        'sql'        => "blob NULL",
        'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
    ],
    'memberHosts'        => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['memberHosts'],
        'exclude'          => true,
        'search'           => true,
        'inputType'        => 'select',
        'options_callback' => ['tl_calendar_events_plus', 'getMemberDocents'],
        'eval'             => ['multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'],
        'sql'              => "blob NULL",
    ],
    'eventtypes'         => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventtypes'],
        'exclude'          => true,
        'search'           => true,
        'inputType'        => 'select',
        'options_callback' => ['tl_calendar_events_plus', 'getEventTypes'],
        'eval'             => ['multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'],
        'sql'              => "blob NULL",
    ],
    'website'            => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['website'],
        'inputType' => 'text',
        'exclude'   => true,
        'eval'      => ['tl_class' => 'w50', 'rgxp' => 'url', 'maxlength' => 255],
        'sql'       => "varchar(255) NOT NULL default ''",
    ],
    // keyword field
    'q'                  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['q'],
        'inputType' => 'text',
        'eval'      => ['placeholder' => &$GLOBALS['TL_LANG']['tl_calendar_events']['placeholders']['q']],
    ],
    // dates field
    'dates'              => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['dates'],
        'inputType' => 'checkbox',
        'eval'      => [
            'multiple'    => true,
            'placeholder' => &$GLOBALS['TL_LANG']['tl_calendar_events']['placeholders']['dates'],
            'tl_class'    => 'hidden',
        ],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'linkedMembers'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['linkedMembers'],
        'inputType' => 'tagsinput',
        'sql'       => "blob NULL",
        'eval'      => [
            'placeholder'   => &$GLOBALS['TL_LANG']['tl_calendar_events']['placeholders']['linkedMembers'],
            'freeInput'     => false,
            'multiple'      => true,
            'mode'          => \TagsInput::MODE_REMOTE,
            'tags_callback' => [['tl_calendar_events_plus', 'getMembers']],
            'remote'        => [
                'fields'       => ['firstname', 'lastname', 'id'],
                'format'       => '%s %s [ID:%s]',
                'queryField'   => 'lastname',
                'queryPattern' => '%QUERY%',
                'foreignKey'   => 'tl_member.id',
                'limit'        => 10,
            ],
        ],
    ],
];

$arrDca['fields'] += $arrFields;

$arrDca['fields']['location']['eval']['tl_class'] = 'w50 clr';

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

    public static function filterSubEvents()
    {
        $arrDca = &$GLOBALS['TL_DCA']['tl_calendar_events'];

        if (\Input::get('table') == 'tl_calendar_events') {
            $intEpid = \Input::get('epid');

            if ($intEpid) {
                if (($objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findBy(['tl_calendar_events.parentEvent=?', 'tl_calendar_events.id!=tl_calendar_events.parentEvent'], [$intEpid], ['order' => 'title'])) !== null) {
                    while ($objEvents->next()) {
                        $arrDca['list']['sorting']['root'][] = $objEvents->id;
                    }
                } else {
                    $arrDca['list']['sorting']['root'] = [-1]; // don't display anything
                }
            } else {
                $arrDca['list']['sorting']['filter'][] = ['(parentEvent=? OR id=parentEvent)', 0];
            }
        }
    }

    public function editParentEvent(DataContainer $objDc)
    {
        return \HeimrichHannot\Haste\Dca\General::getModalEditLink('calendar', $objDc->value, $GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1], 'tl_calendar_events');
    }

    public function showSubEvents($row, $href, $label, $title, $icon, $attributes)
    {
        return '<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;pid=' . $row['pid'] . '&amp;epid=' . $row['id'] . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    public function setDefaultParentEvent($objDc)
    {
        $intId            = \Input::get('id');
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
        $arrRooms = [];

        if (($objRoomArchives = \HeimrichHannot\CalendarPlus\CalendarRoomArchiveModel::findByPid($objDc->activeRecord->pid)) !== null) {
            foreach ($objRoomArchives as $objRoomArchive) {
                if (($objRooms = \HeimrichHannot\CalendarPlus\CalendarRoomModel::findByPid($objRoomArchive->id)) !== null) {
                    foreach ($objRooms as $objRoom) {
                        $arrRooms[$objRoomArchive->title][$objRoom->id] = $objRoom->title;
                    }
                }
            }
        }

        return $arrRooms;
    }

    public function getMemberDocents(DataContainer $objDc = null)
    {
        if (!$objDc || !($objCalendar = CalendarPlusModel::findByPk($objDc->activeRecord->pid))) {
            return [];
        }

        $arrOptions  = [];

        $arrMemberDocentGroups = deserialize($objCalendar->memberDocentGroups, true);

        if (!$objCalendar->addMemberDocentGroups || empty($arrMemberDocentGroups)) {
            return $arrOptions;
        }

        $objMembers = \HeimrichHannot\MemberPlus\MemberPlusMemberModel::findActiveByGroups($arrMemberDocentGroups);

        if ($objMembers === null) {
            return $arrOptions;
        }

        while ($objMembers->next()) {
            $arrTitle = [$objMembers->academicTitle, $objMembers->firstname, $objMembers->lastname];

            if (empty($arrTitle)) {
                continue;
            }

            $arrOptions[$objMembers->id] = implode(' ', $arrTitle);
        }

        return $arrOptions;
    }

    public function getEventTypes(DataContainer $objDc = null)
    {
        $arrOptions = [];

        $objCalendar = CalendarPlusModel::findByPk($objDc->activeRecord->pid);

        if ($objCalendar === null) {
            return $arrOptions;
        }

        // get additional archives from calendar config
        $arrArchiveIds = deserialize($objCalendar->eventTypeArchives, true);

        $objCurrentEventTypeArchives = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findBy('pid', $objDc->activeRecord->pid);

        if ($objCurrentEventTypeArchives !== null) {
            $arrArchiveIds = array_merge($arrArchiveIds, $objCurrentEventTypeArchives->fetchEach('id'));
        }

        $arrArchiveTitles     = [];
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

    /**
     * get member by last name from input
     *
     * @param \DataContainer $dc
     *
     * @return array
     */
    public function getMembers($arrOption, \DataContainer $dc)
    {
        if ($arrOption['value'] == $dc->id) {
            return null;
        }

        return $arrOption;
    }


    public function getParentEventChoices(\DataContainer $dc)
    {
        $choices = [];

        $query = "SELECT id, title FROM tl_calendar_events WHERE parentEvent=0 and id!=? ORDER BY title";
        $events = Database::getInstance()->prepare($query)->execute($dc->id);

        if (null === $events) {
            return $choices;
        }

        return $events->fetchEach('title');
    }
}