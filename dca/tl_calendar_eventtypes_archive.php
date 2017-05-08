<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package extended_events
 * @author  Oliver Janke <o.janke@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Table tl_calendar_eventtypes_archive
 */
$GLOBALS['TL_DCA']['tl_calendar_eventtypes_archive'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'ptable'           => 'tl_calendar',
        'ctable'           => ['tl_calendar_eventtypes'],
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'onload_callback'  => [
            ['tl_calendar_eventtypes_archive', 'checkPermission'],
        ],
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list'        => [
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['title'],
            'headerFields'          => ['title', 'jumpTo', 'tstamp', 'protected', 'allowComments', 'makeFeed'],
            'panelLayout'           => 'filter;sort,search,limit',
            'child_record_callback' => ['tl_calendar_eventtypes_archive', 'listEventtypeArchives'],
            'child_record_class'    => 'no_padding',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['edit'],
                'href'  => 'table=tl_calendar_eventtypes',
                'icon'  => 'edit.gif',
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['editmeta'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
            ],
            'copy'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['copy'],
                'href'  => 'act=paste&amp;mode=copy',
                'icon'  => 'copy.gif',
            ],
            'cut'        => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['cut'],
                'href'  => 'act=paste&amp;mode=cut',
                'icon'  => 'cut.gif',
            ],
            'delete'     => [
                'label'      => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle'     => [
                'label'           => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['tl_calendar_eventtypes', 'toggleIcon'],
            ],
            'show'       => [
                'label' => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes'    => [
        '__selector__' => ['published'],
        'default'      => '{title_legend},title,alias;{publish_legend},published;{expert_legend:hide},cssClass',
    ],

    // Subpalettes
    'subpalettes' => [
        'published' => 'start,stop',
    ],

    // Fields
    'fields'      => [
        'id'        => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid'       => [
            'foreignKey' => 'tl_calendar.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'alias'     => [
            'label'         => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['alias'],
            'exclude'       => true,
            'search'        => true,
            'inputType'     => 'text',
            'eval'          => ['rgxp' => 'alias', 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'save_callback' => [
                ['tl_calendar_eventtypes_archive', 'generateAlias'],
            ],
            'sql'           => "varchar(128) COLLATE utf8_bin NOT NULL default ''",
        ],
        'published' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['published'],
            'exclude'   => true,
            'filter'    => true,
            'flag'      => 2,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true, 'doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'start'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'cssClass'  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_calendar_eventtypes_archive']['cssClass'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50 clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
    ],
];


/**
 * Class tl_calendar_eventtypes_archive
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @package extended_events
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 **/
class tl_calendar_eventtypes_archive extends Backend
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

        $objAlias = $this->Database->prepare("SELECT id FROM tl_calendar_eventtypes_archive WHERE alias=?")->execute($varValue);

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
     * Add the type of input field
     *
     * @param array
     *
     * @return string
     */
    public function listEventtypeArchives($arrRow)
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
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_calendar_eventtypes_archive::published', 'alexf'))
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
        if (!$this->User->hasAccess('tl_calendar_eventtypes_archive::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish promoters ID "' . $intId . '"', __METHOD__, TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_calendar_eventtypes_archive', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_calendar_eventtypes_archive']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_calendar_eventtypes_archive']['fields']['published']['save_callback'] as $callback)
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
        $this->Database->prepare("UPDATE tl_calendar_eventtypes_archive SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);

        $objVersions->create();
        $this->log(
            'A new version of record "tl_calendar_eventtypes_archive.id=' . $intId . '" has been created' . $this->getParentEntries('tl_calendar_eventtypes_archive', $intId),
            __METHOD__,
            TL_GENERAL
        );

        // Update the RSS feed (for some reason it does not work without sleep(1))
        sleep(1);
        $this->import('Calendar');
        $this->Calendar->generateFeedsByCalendar(CURRENT_ID);
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
            $root = [0];
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
                    $this->log('Not enough permissions to create eventtype-archives in calendar ID "' . Input::get('pid') . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
                break;

            case 'cut':
            case 'copy':
                if (!in_array(Input::get('pid'), $root))
                {
                    $this->log(
                        'Not enough permissions to ' . Input::get('act') . ' eventtype-achives ID "' . $id . '" to calendar ID "' . Input::get('pid') . '"',
                        __METHOD__,
                        TL_ERROR
                    );
                    $this->redirect('contao/main.php?act=error');
                }
            // NO BREAK STATEMENT HERE

            case 'edit':
            case 'show':
            case 'delete':
            case 'toggle':
                $objEventtype = $this->Database->prepare("SELECT pid FROM tl_calendar_eventtypes_archive WHERE id=?")->limit(1)->execute($id);

                if ($objEventtype->numRows < 1)
                {
                    $this->log('Invalid event ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                if (!in_array($objEventtype->pid, $root))
                {
                    $this->log(
                        'Not enough permissions to ' . Input::get('act') . ' eventtype-achives ID "' . $id . '" of calendar ID "' . $objEventtype->pid . '"',
                        __METHOD__,
                        TL_ERROR
                    );
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

                $objEventtype = $this->Database->prepare("SELECT id FROM tl_calendar_eventtypes_archive WHERE pid=?")->execute($id);

                if ($objEventtype->numRows < 1)
                {
                    $this->log('Invalid calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }

                $session                   = $this->Session->getData();
                $session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objEventtype->fetchEach('id'));
                $this->Session->setData($session);
                break;

            default:
                if (strlen(Input::get('act')))
                {
                    $this->log('Invalid command "' . Input::get('act') . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
                elseif (!in_array($id, $root))
                {
                    $this->log('Not enough permissions to access calendar ID "' . $id . '"', __METHOD__, TL_ERROR);
                    $this->redirect('contao/main.php?act=error');
                }
                break;
        }
    }
}
