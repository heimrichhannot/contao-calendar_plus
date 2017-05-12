<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @package ${CARET}
 * @author  Martin Kunitzsch <m.kunitzsch@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus;


use HeimrichHannot\Modal\ModalController;
use HeimrichHannot\Modal\ModalModel;

class Hooks extends \Controller
{
    public function addEventDetailsToTemplate(&$objTemplate, $event, $objModule)
    {
        if (!(($objModule->useModal && $event['source'] == 'default')))
        {
            return false;
        }

        $objArchive = CalendarPlusModel::findById($objTemplate->pid);
        $objJumpTo  = \PageModel::findPublishedById($objArchive->jumpTo);

        if ($objJumpTo === null || !$objJumpTo->linkModal)
        {
            return false;
        }

        $objModal = ModalModel::findPublishedByIdOrAlias($objJumpTo->modal);

        if ($objModal === null)
        {
            return false;
        }

        $objJumpTo = \PageModel::findWithDetails($objJumpTo->id);

        $arrConfig = ModalController::getModalConfig($objModal->current(), $objJumpTo->layout);

        $blnAjax     = true;
        $blnRedirect = true;

        $objTemplate->link         = ModalController::generateModalUrl($event, $objArchive->jumpTo, $blnAjax, $blnRedirect);
        $objTemplate->linkHeadline = ModalController::convertLinkToModalLink($objTemplate->linkHeadline, $objTemplate->link, $arrConfig, $blnRedirect);
        $objTemplate->more         = ModalController::convertLinkToModalLink($objTemplate->more, $objTemplate->link, $arrConfig, $blnRedirect);

        unset($event['alias']);
    }
}