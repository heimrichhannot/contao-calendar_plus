<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;

use HeimrichHannot\CalendarPlus\Controller\PromoterController;

class ContentCalendarPromoterList extends \ContentElement
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'ce_calendar_promoterlist';

    protected $objPromoters;

    /**
     * @var PromoterController
     */
    protected $controller;

    /**
     * Parse the template
     *
     * @return string
     */
    public function generate()
    {
        $this->cal_promoters = deserialize($this->cal_promoters, true);

        $this->Controller = new PromoterController($this->objModel);

        return parent::generate();
    }

    protected function compile()
    {
        $this->objPromoters = CalendarPromotersModel::findPublishedByIds($this->cal_promoters);

        if ($this->objPromoters === null)
        {
            $this->Template->empty = $GLOBALS['TL_LANG']['cal_promoterlist']['emptyList'];

            return;
        }

        $i            = 0;
        $arrPromoters = [];
        $arrList      = $this->objPromoters->fetchAll();
        $this->objPromoters->reset();

        while ($this->objPromoters->next())
        {
            $arrPromoters[] = $this->Controller->parsePromoter($this->objPromoters->current(), $i, $arrList);
            $i++;
        }

        $this->Template->promoters = $arrPromoters;
    }
}