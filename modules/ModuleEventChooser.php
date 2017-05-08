<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package calendar_plus
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;

class ModuleEventChooser extends \Module
{

    protected $strTemplate = 'mod_event_chooser';

    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate           = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### EVENT CHOOSER ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $this->loadLanguageFile('tl_calendar_events');

        $objEvents = \HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findAll(
            [
                'order' => 'startDate DESC',
            ]
        );

        // HOOK: modify event objects for the chooser
        if (isset($GLOBALS['TL_HOOKS']['getEventObjectsForChooser']) && is_array($GLOBALS['TL_HOOKS']['getEventObjectsForChooser']))
        {
            foreach ($GLOBALS['TL_HOOKS']['getEventObjectsForChooser'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($objEvents);
            }
        }

        $items = [];
        if ($objEvents !== null)
        {
            while ($objEvents->next())
            {
                $items[$objEvents->id] = [
                    'title' => $objEvents->shortTitle ? $objEvents->shortTitle : $objEvents->title,
                    'date'  => date('d.m.Y', $objEvents->startDate),
                ];
            }
        }

        // HOOK: modify event items for the chooser
        if (isset($GLOBALS['TL_HOOKS']['getEventItemsForChooser']) && is_array($GLOBALS['TL_HOOKS']['getEventItemsForChooser']))
        {
            foreach ($GLOBALS['TL_HOOKS']['getEventItemsForChooser'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($objEvents, $items);
            }
        }

        $this->Template->items = $items;
        if ($this->jumpTo)
        {
            $this->Template->action = $this->generateFrontendUrl($this->jumpTo);
        }
        else
        {
            $this->Template->action = '';
        }
    }
}