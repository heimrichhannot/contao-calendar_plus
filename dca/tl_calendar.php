<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package extendes_events
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_calendar'];

/**
 * Config
 */
$dc['list']['sorting']['fields'] = ['root', 'title'];

/**
 * Selector
 */
$dc['palettes']['__selector__'][] = 'addMemberDocentGroups';

/**
 * Palettes
 */
$dc['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{root_legend},root;', $dc['palettes']['default']);
$dc['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{join_legend},eventTypeArchives;', $dc['palettes']['default']);
$dc['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{docent_legend},addMemberDocentGroups;', $dc['palettes']['default']);
$dc['palettes']['default'] = str_replace('jumpTo;', 'jumpTo;{files_legend},uploadFolder;', $dc['palettes']['default']);

/**
 * Subpalettes
 */
$dc['subpalettes']['addMemberDocentGroups'] = 'memberDocentGroups';

/**
 * Operations
 */
array_insert(
    $dc['list']['operations'],
    2,
    [
        'promoters'              => [
            'label'           => &$GLOBALS['TL_LANG']['tl_calendar']['promoters'],
            'href'            => 'table=tl_calendar_promoters',
            'icon'            => 'system/modules/calendar_plus/assets/img/icons/promoters.png',
            'button_callback' => ['tl_calendar_plus', 'editPromoters'],
        ],
        'docents'                => [
            'label'           => &$GLOBALS['TL_LANG']['tl_calendar']['docents'],
            'href'            => 'table=tl_calendar_docents',
            'icon'            => 'system/modules/calendar_plus/assets/img/icons/docents.png',
            'button_callback' => ['tl_calendar_plus', 'editDocents'],
        ],
        'eventtypearchives'      => [
            'label'           => &$GLOBALS['TL_LANG']['tl_calendar']['eventtypearchives'],
            'href'            => 'table=tl_calendar_eventtypes_archive',
            'icon'            => 'system/modules/calendar_plus/assets/img/icons/eventtypes.png',
            'button_callback' => ['tl_calendar_plus', 'editEventtypeArchives'],
        ],
        'calendar_room_archives' => [
            'label'           => &$GLOBALS['TL_LANG']['tl_calendar']['calendarroomarchives'],
            'href'            => 'table=tl_calendar_room_archive',
            'icon'            => 'system/modules/calendar_plus/assets/img/icons/rooms.png',
            'button_callback' => ['tl_calendar_plus', 'editCalendarRoomArchives'],
        ],
    ]
);

/**
 * Fields
 */

$arrFields = [
    'root'                  => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar']['root'],
        'inputType'        => 'select',
        'options_callback' => ['tl_calendar_plus', 'getRootPages'],
        'eval'             => ['includeBlankOption' => true],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
    ],
    'eventTypeArchives'     => [
        'label'            => &$GLOBALS['TL_LANG']['tl_calendar']['eventTypeArchives'],
        'exclude'          => true,
        'inputType'        => 'checkbox',
        'options_callback' => ['tl_calendar_plus', 'getEventTypeArchives'],
        'eval'             => ['multiple' => true],
        'sql'              => "blob NULL",
    ],
    'addMemberDocentGroups' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_calendar']['addMemberDocentGroups'],
        'exclude'   => true,
        'filter'    => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'memberDocentGroups'    => [
        'label'      => &$GLOBALS['TL_LANG']['tl_calendar']['memberDocentGroups'],
        'exclude'    => true,
        'inputType'  => 'checkbox',
        'foreignKey' => 'tl_member_group.name',
        'eval'       => ['mandatory' => true, 'multiple' => true],
        'sql'        => "blob NULL",
        'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
    ],
    'uploadFolder'          => [
        'label'         => &$GLOBALS['TL_LANG']['tl_calendar']['uploadFolder'],
        'exclude'       => true,
        'inputType'     => 'fileTree',
        'load_callback' => [
            ['tl_calendar_plus', 'setDefaultUploadFolder'],
        ],
        'save_callback' => [
            ['tl_calendar_plus', 'setDefaultUploadFolder'],
        ],
        'eval'          => ['filesOnly' => false, 'fieldType' => 'radio', 'doNotCopy' => true],
        'sql'           => "binary(16) NULL",
    ],
];

$dc['fields'] = array_merge($dc['fields'], $arrFields);


class tl_calendar_plus extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function setDefaultUploadFolder($varValue, \DataContainer $dc)
    {
        if ($varValue == '')
        {
            $objFolder = new \Folder(sprintf('files/calendar/%d/', $dc->id));

            if (\Validator::isUuid($objFolder->getModel()->uuid))
            {
                return $objFolder->getModel()->uuid;
            }

            return null;
        }

        return $varValue;
    }

    /**
     * Return the manage room archives button
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public function editCalendarRoomArchives($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('rooms', 'calendarp')
            ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label)
              . '</a> '
            : Image::getHtml(
                preg_replace('/\.png/i', '_.png', $icon)
            ) . ' ';
    }

    /**
     * Return the manage promoters button
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public function editPromoters($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('promoters', 'calendarp')
            ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label)
              . '</a> '
            : Image::getHtml(
                preg_replace('/\.png/i', '_.png', $icon)
            ) . ' ';
    }


    /**
     * Return the manage docents button
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public function editDocents($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('docents', 'calendarp')
            ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label)
              . '</a> '
            : Image::getHtml(
                preg_replace('/\.png/i', '_.png', $icon)
            ) . ' ';
    }


    /**
     * Return the manage eventtypearchives button
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
     * @return string
     */
    public function editEventtypeArchives($row, $href, $label, $title, $icon, $attributes)
    {
        return $this->User->hasAccess('eventtypes', 'calendarp')
            ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label)
              . '</a> '
            : Image::getHtml(
                preg_replace('/\.png/i', '_.png', $icon)
            ) . ' ';
    }

    public function getEventTypeArchives(DataContainer $dc)
    {
        $arrOptions = [];

        $objArchives = \HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel::findWithoutPids([$dc->id]);

        if ($objArchives === null)
        {
            return $arrOptions;
        }

        while ($objArchives->next())
        {
            $objCalendar = \HeimrichHannot\CalendarPlus\CalendarPlusModel::findByPk($objArchives->pid);

            $arrOptions[$objArchives->id] = $objCalendar->title . ' - ' . $objArchives->title;
        }

        return $arrOptions;
    }


    public function getRootPages(\DataContainer $dc)
    {
        $arrOptions = [];

        $objPages = \PageModel::findBy('type', 'root');

        if ($objPages === null)
        {
            return $arrOptions;
        }

        return $objPages->fetchEach('title');
    }
}