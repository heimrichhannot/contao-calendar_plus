<?php

namespace HeimrichHannot\CalendarPlus\EventListener\Hooks;

use Contao\Backend;
use Contao\CalendarModel;
use Contao\Input;
use Contao\System;
use Contao\Template;
use HeimrichHannot\CalendarPlus\CalendarPlusEventsModel;
use HeimrichHannot\UtilsBundle\Util\Utils;

class ParseTemplateListener
{
    public function __invoke(Template $template): void
    {
        if ($template->getName() === 'be_main') {
            $archiveId = Input::get('pid');
            $eventId = Input::get('epid');
            if (!$eventId || !$archiveId) {
                return;
            }

            $archiveModel = CalendarModel::class::findByPk($archiveId);
            $eventModel = CalendarPlusEventsModel::findByPk($eventId);
            if (!$eventModel) {
                return;
            }

            $utils = System::getContainer()->get(Utils::class);

            $headlineParts = explode('> <', $template->headline);
            unset($headlineParts[array_key_last($headlineParts)]);
            $eventUrl = $utils->routing()->generateBackendRoute([
                'do' => 'calendar',
                'table' => 'tl_calendar_events',
                'id' => $eventId,
                'act' => 'edit',
            ]);
            $headlineParts[] = 'span><a href="'. $eventUrl .'">'.$eventModel->title.'</a></span';
            $headlineParts[] = 'span>'.$GLOBALS['TL_LANG']['MSC']['subevents'].'</span>';

            $template->headline = implode('> <', $headlineParts);

        }
    }
}