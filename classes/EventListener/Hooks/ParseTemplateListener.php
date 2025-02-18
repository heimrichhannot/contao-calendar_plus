<?php

namespace HeimrichHannot\CalendarPlus\EventListener\Hooks;

use Contao\CalendarModel;
use Contao\Input;
use Contao\Template;
use HeimrichHannot\CalendarPlus\CalendarPlusEventsModel;

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

            $headlineParts = explode('> <', $template->headline);
            unset($headlineParts[array_key_last($headlineParts)]);
            $headlineParts[] = 'span>'.$eventModel->title.'</span';
            $headlineParts[] = 'span>'.$GLOBALS['TL_LANG']['MSC']['subevents'].'</span>';

            $template->headline = implode('> <', $headlineParts);

        }
    }
}