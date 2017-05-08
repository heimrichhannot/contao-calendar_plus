<?php

\Controller::loadDataContainer('tl_module');
\Controller::loadLanguageFile('tl_module');
\Controller::loadLanguageFile('tl_calendar_promoters');

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Palettes
 */
$dc['palettes']['promoterlist'] =
    '{type_legend},type,headline;{promoter_config_legend},cal_calendar,cal_promoterTypes,cal_promoters,cal_promoterTemplate;{template_legend:hide},customTpl,size;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';


/**
 * Fields
 */
$arrFields = [
    'cal_calendar'         => [
        'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_calendar'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['tl_module_calendar', 'getCalendars'],
        'eval'             => ['mandatory' => true, 'chosen' => true, 'submitOnChange' => true],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
    ],
    'cal_promoterTypes'    => [
        'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_promoterTypes'],
        'exclude'          => true,
        'inputType'        => 'checkbox',
        'options_callback' => ['HeimrichHannot\\CalendarPlus\\Utils\\Options', 'getPromoterTypes'],
        'reference'        => &$GLOBALS['TL_LANG']['tl_calendar_promoters']['type'],
        'eval'             => ['mandatory' => true, 'multiple' => true, 'submitOnChange' => true],
        'sql'              => "blob NULL",
    ],
    'cal_promoters'        => [
        'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_promoters'],
        'exclude'          => true,
        'inputType'        => 'checkboxWizard',
        'options_callback' => ['tl_content_calendar_plus', 'getPromoters'],
        'eval'             => ['mandatory' => true, 'multiple' => true],
        'sql'              => "blob NULL",
    ],
    'cal_promoterTemplate' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_module']['cal_promoterTemplate'],
        'default'          => 'cal_promoter_default',
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['tl_module_calendar_plus', 'getPromoterTemplates'],
        'eval'             => ['tl_class' => 'w50', 'includeBlankOption' => true],
        'sql'              => "varchar(64) NOT NULL default ''",
    ],
];

$dc['fields'] = array_merge($dc['fields'], $arrFields);

class tl_content_calendar_plus extends Backend
{

    public function getPromoters($dc)
    {
        $arrOptions = [];

        if ($dc->activeRecord === null || !$dc->activeRecord->cal_calendar)
        {
            return $arrOptions;
        }

        $arrTypes = deserialize($dc->activeRecord->cal_promoterTypes, true);

        if (empty($arrTypes))
        {
            return $arrOptions;
        }

        $objPromoters = HeimrichHannot\CalendarPlus\CalendarPromotersModel::findPublishedByPidsAndTypes([$dc->activeRecord->cal_calendar], $arrTypes);

        if ($objPromoters === null)
        {
            return $arrOptions;
        }

        return $objPromoters->fetchEach('title');
    }

}