<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class ExtendedEvents extends Frontend
{

	public function parseBackendTemplate($strContent, $strTemplate)
	{
		if ($_GET['epid'])
		{
			$objEvent = \CalendarEventsModel::findByPk($_GET['epid']);
			if ($objEvent !== null) {
				if ($this->Input->get('table') == 'tl_content')
				{
					$strContent = preg_replace('/<a href=\"(.*?)\" class=\"header_back\"/', '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&epid=' . $_GET['epid'] . '&pid=' . $_GET['pid'] . '"', $strContent);
				}
				else if ($objEvent->parentEvent)
				{
					$strContent = preg_replace('/<a href=\"(.*?)\" class=\"header_back\"/', '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&epid=' . $objEvent->parentEvent . '&pid=' . $_GET['pid'] . '"', $strContent);
				}
				else
					$strContent = preg_replace('/<a href=\"(.*?)\" class=\"header_back\"/', '<a class="header_back" href="contao/main.php?do=calendar&table=tl_calendar_events&id=' . $_GET['pid'] . '"', $strContent);
			}
		}
		return $strContent;
	}
	
	public function getAllParentEvents($arrEvents, $arrCalendars, $intStart, $intEnd, \Module $objModule)
	{
		$arrResult = array();
		if ($objModule->hideSubEvents) {
			foreach ($arrEvents as $date => $eventsThisDay) {
				foreach ($eventsThisDay as $startDateTime => $eventsThisDateTime) {
					foreach ($eventsThisDateTime as $currentEventThisDateTime) {
						if (!$currentEventThisDateTime['parentEvent'])
							$arrResult[$date][$startDateTime][] = $currentEventThisDateTime;
					}
				}
			}
		}
		return $objModule->hideSubEvents ? $arrResult : $arrEvents;
	}

}