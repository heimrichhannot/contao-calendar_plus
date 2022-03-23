<?php

$GLOBALS['TL_DCA']['tl_calendar_room'] = [
    'config'      => [
        'dataContainer'     => 'Table',
        'ptable'            => 'tl_calendar_room_archive',
        'enableVersioning'  => true,
        'onload_callback'   => [
            ['tl_calendar_room', 'checkPermission'],
        ],
        'onsubmit_callback' => [
            'setDateAdded' => ['HeimrichHannot\\HastePlus\\Utilities', 'setDateAdded'],
        ],
        'sql'               => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list'        => [
        'label'             => [
            'fields' => ['id'],
        ],
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['title'],
            'headerFields'          => ['title', 'jumpTo', 'tstamp', 'protected', 'allowComments', 'makeFeed'],
            'panelLayout'           => 'filter;sort,search,limit',
            'child_record_callback' => ['tl_calendar_room', 'listRooms'],
            'child_record_class'    => 'no_padding',

        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_room']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_room']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_calendar_room']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_calendar_room']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_calendar_room', 'toggleIcon'],
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_room']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],
    'palettes'    => [
        '__selector__' => ['published'],
        'default'      => '{title_legend},title,shortTitle,alias;{address_legend},street,postal,city,country,singleCoords;{room_legend},floor,isIndoor;{publish_legend},published',
    ],
    'subpalettes' => [
        'published' => 'start,stop',
    ],
    'fields'      => [
        'id'           => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid'          => [
            'foreignKey' => 'tl_calendar_room_archive.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp'       => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'dateAdded'    => [
            'label'   => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'sorting' => true,
            'flag'    => 6,
            'eval'    => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql'     => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'w50', 'mandatory' => true],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'shortTitle'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['shortTitle'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 128, 'tl_class' => 'w50'],
            'sql'       => "varchar(128) NOT NULL default ''",
        ],
        'alias'        => [
            'label'         => &$GLOBALS['TL_LANG']['tl_calendar_room']['alias'],
            'exclude'       => true,
            'search'        => true,
            'inputType'     => 'text',
            'eval'          => ['rgxp' => 'alias', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'save_callback' => [
                ['tl_calendar_room', 'generateAlias'],
            ],
            'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''",
        ],
        'street'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['street'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'postal'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['postal'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 32, 'tl_class' => 'w50'],
            'sql'       => "varchar(32) NOT NULL default ''",
        ],
        'city'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['city'],
            'exclude'   => true,
            'filter'    => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'country'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['country'],
            'exclude'   => true,
            'filter'    => true,
            'sorting'   => true,
            'inputType' => 'select',
            'options'   => System::getCountries(),
            'eval'      => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(2) NOT NULL default ''",
        ],
        'singleCoords' => [
            'label'         => &$GLOBALS['TL_LANG']['tl_calendar_room']['singleCoords'],
            'exclude'       => true,
            'search'        => true,
            'inputType'     => 'text',
            'eval'          => ['maxlength' => 64, 'tl_class' => 'w50'],
            'sql'           => "varchar(64) NOT NULL default ''",
            'save_callback' => [
                ['tl_calendar_room', 'generateSingleCoords'],
            ],
        ],
        'floor'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['floor'],
            'exclude'   => true,
            'search'    => true,
            'default'   => 0,
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50', 'rgxp' => 'digit'],
            'sql'       => "int(10) NOT NULL",
        ],
        'isIndoor'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['isIndoor'],
            'exclude'   => true,
            'filter'    => true,
            'flag'      => 2,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50 clr'],
            'default'   => true,
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'published'    => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['published'],
            'exclude'   => true,
            'filter'    => true,
            'flag'      => 2,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true, 'doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'start'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_room']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
    ],
];


class tl_calendar_room extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function listRooms($arrRow)
    {
        return '<div class="tl_content_left">' . $arrRow['title'] . ' <span style="color:#b3b3b3; padding-left:3px">[' . \Date::parse(
                Config::get('datimFormat'),
                trim($arrRow['dateAdded'])
            ) . ']</span></div>';
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
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_calendar_room::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published'])
        {
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
        if (!$this->User->hasAccess('tl_calendar_room::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish room ID "' . $intId . '"', __METHOD__, TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_calendar_room', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_calendar_room']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_calendar_room']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, ($dc ?: $this));
                }
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_calendar_room SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);

        $objVersions->create();
        $this->log('A new version of record "tl_calendar_room.id=' . $intId . '" has been created' . $this->getParentEntries('tl_calendar_room', $intId), __METHOD__, TL_GENERAL);

        // Update the RSS feed (for some reason it does not work without sleep(1))
        sleep(1);
        $this->import('Calendar');
        $this->Calendar->generateFeedsByCalendar(CURRENT_ID);
    }

    /**
     * Check permissions to edit table tl_calendar_events
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
            $root = [0];
        }
        else
        {
            $root = $this->User->calendars;
        }

        $id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

        $objRoomArchive = \HeimrichHannot\CalendarPlus\CalendarRoomArchiveModel::findByPk($id);

        if ($objRoomArchive !== null)
        {
            $pid = $objRoomArchive->pid;
        }

        // Check current action
        switch (Input::get('act'))
        {
            case 'paste':
                // Allow
                break;

            case 'create':
                if (!strlen(Input::get('pid')) || !in_array($pid, $root))
                {
                    $this->log('Not enough permissions to create room in calendar ID "' . Input::get('pid') . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
                break;

            case 'cut':
            case 'copy':
                if (!in_array($pid, $root))
                {
                    $this->log('Not enough permissions to ' . Input::get('act') . ' room ID "' . $id . '" to calendar ID "' . $pid . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
            // NO BREAK STATEMENT HERE

            case 'edit':
            case 'show':
            case 'delete':
            case 'toggle':
                $objRoom = $this->Database->prepare("SELECT pid FROM tl_calendar_room WHERE id=?")->limit(1)->execute($id);

                if ($objRoom->numRows < 1)
                {
                    $this->log('Invalid room ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                $objRoomArchive = $this->Database->prepare("SELECT pid FROM tl_calendar_room_archive WHERE id=?")->limit(1)->execute($objRoom->pid);

                if ($objRoomArchive->numRows < 1)
                {
                    $this->log('Invalid room archive ID "' . $objRoom->pid . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                if (!in_array($objRoomArchive->pid, $root))
                {
                    $this->log('Not enough permissions to ' . Input::get('act') . ' room ID "' . $id . '" of calendar ID "' . $objRoomArchive->pid . '"', __METHOD__, TL_ERROR);
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
                    $this->log('Not enough permissions to access calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                $objRoomArchive = $this->Database->prepare("SELECT pid FROM tl_calendar_room_archive WHERE pid=?")->execute($id);

                if ($objRoomArchive->numRows < 1)
                {
                    $this->log('Invalid calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                $objRooms = $this->Database->prepare("SELECT pid FROM tl_calendar_room WHERE pid=?")->execute($objRoomArchive->id);

                $session                   = $this->Session->getData();
                $session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objRooms->fetchEach('id'));
                $this->Session->setData($session);
                break;

            default:
                if (strlen(Input::get('act')))
                {
                    $this->log('Invalid command "' . Input::get('act') . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
                elseif (!in_array($pid, $root))
                {
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
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue  = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->title));
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_calendar_room WHERE alias=?")->execute($varValue);

        // Check whether the alias exists
        if ($objAlias->numRows > 1 && !$autoAlias)
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if ($objAlias->numRows && $autoAlias)
        {
            $varValue .= '-' . $dc->id;
        }

        return $varValue;
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
        if ($varValue != '')
        {
            return $varValue;
        }

        $strAddress = '';

        if ($dc->activeRecord->street != '')
        {
            $strAddress .= $dc->activeRecord->street;
        }

        if ($dc->activeRecord->postal != '' && $dc->activeRecord->city)
        {
            $strAddress .= ($strAddress ? ',' : '') . $dc->activeRecord->postal . ' ' . $dc->activeRecord->city;
        }

        if (($strCoords = $this->generateCoordsFromAddress($strAddress, $dc->activeRecord->country ?: 'de')) !== false)
        {
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
        if (!in_array('dlh_geocode', \ModuleLoader::getActive()))
        {
            return false;
        }

        return \delahaye\GeoCode::getCoordinates($strAddress, $strCountry, 'de');
    }

}
