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


/**
 * Class ModuleEventReader
 *
 * Front end module "event reader".
 *
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
 */
class ModuleEventReaderPlus extends EventsPlus
{
	
	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_event';


	/**
	 * Current event model
	 * @var CalendarPlusEventsModel
	 */
	protected $objEvent;
	
	protected $registration;

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		
		if (TL_MODE == 'BE') {
			$objTemplate = new \BackendTemplate('be_wildcard');
			
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['eventreader_plus'][0]) . ' ###';
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		
		
		global $objPage;
		
		// add registration before generating event details, otherwise formhybrid ajax requests wont get triggered
		if(in_array('event_registration', \ModuleLoader::getActive()) && $this->addRegistration && $this->checkConditions() && $this->objEvent->addRegistration)
		{
			if ((!$this->objEvent->codes && !\Input::get('step')) || \Input::get('step') == EventRegistration::STEP_REGISTRATION)
			{
				if($this->registrationFormModule > 0)
				{
					$this->registration = \Controller::getFrontendModule($this->registrationFormModule);
				}
			}
			else if(\Input::get('step') == EventRegistration::STEP_SUMMARY)
			{
				if($this->summaryModule > 0)
				{
					$this->registration = \Controller::getFrontendModule($this->summaryModule);
				}
			}
			else {
				if($this->codeCheckModule > 0)
				{
					$this->registration = \Controller::getFrontendModule($this->codeCheckModule);
				}
			}
		}
		
		if ($this->cal_template_modal) {
			$this->strTemplate  = $this->customTpl ?: 'mod_event_modal';
			$this->cal_template = $this->cal_template_modal;
			
			// list config
			$this->cal_showInModal  = true;
			$this->cal_readerModule = $this->id;
			
			// set modal css ID for generateModal() and parent::generate()
			$arrCss      = deserialize($this->cssID, true);
			$arrCss[0]   = EventsPlusHelper::getCSSModalID($this->id);
			$this->cssID = $arrCss;
			$this->base  = \Controller::generateFrontendUrl($objPage->row());
			
			if ($this->Environment->isAjaxRequest && !$this->isSearchIndexer()) {
				$this->strTemplate = 'mod_event_modal_ajax';
				
				if (!$this->generateAjax())
				{
					return '';
				}
			}
			
			if (!$this->checkConditions()) {
				return $this->generateModal();
			}
		}


		
		return parent::generate();
	}
	
	protected function generateAjax()
	{
		// ajax request ist not delegated to this module
		if (!(\Input::get('scope') == 'modal' && \Input::get('target') == EventsPlusHelper::getCSSModalID($this->id))) {
			return false;
		}
		
		if ($this->checkConditions())
		{
			parent::generate();
			// use output, otherwise page will not be added to search index
			$strOutput = $this->isSearchIndexer() ? $this->Template->output() : $this->Template->parse();
			die(\Controller::replaceInsertTags($strOutput, false));
		}
	}
	
	protected function checkConditions()
	{
		// Set the item from the auto_item parameter
		if (!isset($_GET['events']) && \Config::get('useAutoItem') && isset($_GET['auto_item'])) {
			\Input::setGet('events', \Input::get('auto_item'));
		}
		
		// Do not index or cache the page if no event has been specified
		if (!\Input::get('events')) {
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache    = 0;
			
			return false;
		}
		
		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar));
		
		// Do not index or cache the page if there are no calendars
		if (!is_array($this->cal_calendar) || empty($this->cal_calendar)) {
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache    = 0;
			
			return false;
		}
		
		$this->objEvent = \CalendarEventsModel::findPublishedByParentAndIdOrAlias(\Input::get('events'), $this->cal_calendar);
		
		return true;
	}
	
	protected function generateModal()
	{
		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);
		$this->Template->class = trim('mod_' . $this->type . ' ' . $this->cssID[1]);
		$this->Template->cssID = ($this->cssID[0] != '') ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->base  = $this->base;
		
		if (!empty($this->objModel->classes) && is_array($this->objModel->classes)) {
			$this->Template->class .= ' ' . implode(' ', $this->objModel->classes);
		}
		
		return $this->Template->parse();
	}
	
	protected function generateArrowNavigation($objCurrentEvent, $strUrl)
	{
		$objT = new \FrontendTemplate('eventnavigation_arrows');
		$objT->setData($this->arrData);
		
		$arrIds = array();
		
		// get ids from EventsPlus::getAllEvents
		$session = \Session::getInstance()->getData();
		$arrIds  = $session[CALENDARPLUS_SESSION_EVENT_IDS];
		
		// EventsPlus::getAllEvents did not run before the reader
		// TODO: run EventsPlus::getAllEvents before
		if (!is_array($arrIds) || empty($arrIds)) {
			return;
		}
		
		$prevID       = null;
		$nextID       = null;
		$currentIndex = array_search($objCurrentEvent->id, $arrIds);
		
		// prev only of not first item
		if (isset($arrIds[$currentIndex - 1])) {
			$prevID = $arrIds[$currentIndex - 1];
			
			$objEvent = \CalendarEventsModel::findPublishedByParentAndIdOrAlias($prevID, $this->cal_calendar);
			
			$intStartTime = $objEvent->startTime;
			$intEndTime   = $objEvent->endTime;
			$span         = \Calendar::calculateSpan($intStartTime, $intEndTime);
			
			// Do not show dates in the past if the event is recurring (see #923)
			if ($objEvent->recurring) {
				$arrRange = deserialize($objEvent->repeatEach);
				
				while ($intStartTime < time() && $intEndTime < $objEvent->repeatEnd) {
					$intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
					$intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
				}
			}
			
			if ($objEvent !== null) {
				$objT->prev     = $this->getEventDetails($objEvent, $intStartTime, $intEndTime, $strUrl, 0, $objEvent->pid);
				$objT->prevLink = $GLOBALS['TL_LANG']['event']['prevLink'];
			}
		}
		
		// next only of not last item
		if (isset($arrIds[$currentIndex + 1])) {
			$nextID   = $arrIds[$currentIndex + 1];
			$objEvent = \CalendarEventsModel::findPublishedByParentAndIdOrAlias($nextID, $this->cal_calendar);
			
			$intStartTime = $objEvent->startTime;
			$intEndTime   = $objEvent->endTime;
			$span         = \Calendar::calculateSpan($intStartTime, $intEndTime);
			
			// Do not show dates in the past if the event is recurring (see #923)
			if ($objEvent->recurring) {
				$arrRange = deserialize($objEvent->repeatEach);
				
				while ($intStartTime < time() && $intEndTime < $objEvent->repeatEnd) {
					$intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
					$intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
				}
			}
			
			if ($objEvent !== null) {
				$objT->next     = $this->getEventDetails($objEvent, $intStartTime, $intEndTime, $strUrl, 0, $objEvent->pid);
				$objT->nextLink = $GLOBALS['TL_LANG']['event']['nextLink'];
			}
		}
		
		return $objT->parse();
	}
	
	protected function compile()
	{
		global $objPage;
		
		$this->Template->event   = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back    = $GLOBALS['TL_LANG']['MSC']['goBack'];
		
		
		if ($this->objEvent === null) {
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache    = 0;
			
			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->event = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('events')) . '</p>';
			
			// remove page from search index
			if ($this->cal_showInModal) {
				\HeimrichHannot\SearchPlus\Search::removePageFromIndex(\Environment::get('request'));
			}
			
			return;
		}
		
		// Overwrite the page title (see #2853 and #4955)
		if ($this->objEvent->title != '') {
			$objPage->pageTitle = strip_tags(strip_insert_tags($this->objEvent->title));
		}
		
		// Overwrite the page description
		if ($this->objEvent->teaser != '') {
			$objPage->description = $this->prepareMetaDescription($this->objEvent->teaser);
		}
		
		$strUrl = '';
		
		$objCalendar = \CalendarModel::findByPk($this->objEvent->pid);
		
		// Get the current "jumpTo" page
		if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null) {
			$strUrl = $this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/%s' : '/events/%s'));
		}
		
		$intStartTime = $this->objEvent->startTime;
		$intEndTime   = $this->objEvent->endTime;
		$span         = \Calendar::calculateSpan($intStartTime, $intEndTime);
		
		// Do not show dates in the past if the event is recurring (see #923)
		if ($this->objEvent->recurring) {
			$arrRange = deserialize($this->objEvent->repeatEach);
			
			while ($intStartTime < time() && $intEndTime < $this->objEvent->repeatEnd) {
				$intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
				$intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
			}
		}
		
		$objEvent = (object) $this->getEventDetails($this->objEvent, $intStartTime, $intEndTime, $strUrl, $intStartTime, $this->objEvent->pid);
		
		$arrSubEvents = array();
		
		if (!$this->cal_ungroupSubevents) {
			$objChildEvents = CalendarPlusEventsModel::findPublishedSubEvents($objEvent->id);
			
			if ($objChildEvents !== null) {
				while ($objChildEvents->next()) {
					$arrSubEvents[$objChildEvents->id] = $this->addSingleEvent($objChildEvents, $intStartTime);
				}
			}
		}
		
		if ($objPage->outputFormat == 'xhtml') {
			$strTimeStart = '';
			$strTimeEnd   = '';
			$strTimeClose = '';
		} else {
			$strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $intStartTime) . '">';
			$strTimeEnd   = '<time datetime="' . date('Y-m-d\TH:i:sP', $intEndTime) . '">';
			$strTimeClose = '</time>';
		}
		
		// Get date
		if ($span > 0) {
			$date = $strTimeStart . \Date::parse(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intStartTime) . $strTimeClose . ' - ' . $strTimeEnd . \Date::parse(
					($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat),
					$intEndTime
				) . $strTimeClose;
		} elseif ($intStartTime == $intEndTime) {
			$date = $strTimeStart . \Date::parse($objPage->dateFormat, $intStartTime) . ($objEvent->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) . ')' : '') . $strTimeClose;
		} else {
			$date =
				$strTimeStart . \Date::parse($objPage->dateFormat, $intStartTime) . ($objEvent->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) . $strTimeClose . ' - ' . $strTimeEnd
																										  . \Date::parse($objPage->timeFormat, $intEndTime) . ')' : '') . $strTimeClose;
		}
		
		$until     = '';
		$recurring = '';
		
		// Recurring event
		if ($objEvent->recurring) {
			$arrRange  = deserialize($objEvent->repeatEach);
			$strKey    = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);
			
			if ($objEvent->recurrences > 0) {
				$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], \Date::parse($objPage->dateFormat, $objEvent->repeatEnd));
			}
		}
		
		// Override the default image size
		if ($this->imgSize != '') {
			$size = deserialize($this->imgSize);
			
			if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2])) {
				$objEvent->size = $this->imgSize;
			}
		}
		
		$imgSize = false;
		
		// Override the default image size
		if ($this->imgSize != '') {
			$size = deserialize($this->imgSize);
			
			if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2])) {
				$imgSize = $this->imgSize;
			}
		}
		
		$objTemplate = new \FrontendTemplate($this->cal_template);
		$objTemplate->setData((array) $objEvent);
		$objTemplate->nav = $this->generateArrowNavigation($objEvent, $strUrl);
		
		if ($this->registration !== null)
		{
			$objTemplate->registration = $this->registration;
			$objTemplate->module = $this; // falback
		}
		
		if (is_array($arrSubEvents) && !empty($arrSubEvents)) {
			$strSubEvents = '';
			
			foreach ($arrSubEvents as $subID => $arrSubEvent) {
				$objSubEventTemplate = new \FrontendTemplate($this->cal_templateSubevent);
				$objSubEventTemplate->setData($arrSubEvent);
				$this->addEventDetailsToTemplate($objSubEventTemplate, $arrSubEvent, '', '', $imgSize);
				$strSubEvents .= $objSubEventTemplate->parse() . "\n";
			}
			
			$objTemplate->subEvents = $strSubEvents;
		}
		
		
		if ($this->addShare && in_array('share', $this->Config->getActiveModules())) {
			$objShare           = new \HeimrichHannot\Share\Share($this->objModel, $objEvent);
			$objTemplate->share = $objShare->generate();
		}
		
		
		$objTemplate->addImage = false;
		
		// Add an image
		if ($objEvent->addImage && $objEvent->singleSRC != '') {
			$objModel = \FilesModel::findByUuid($objEvent->singleSRC);
			
			if ($objModel === null) {
				if (!\Validator::isUuid($objEvent->singleSRC)) {
					$objTemplate->text = '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
				}
			} elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
				// Do not override the field now that we have a model registry (see #6303)
				$arrEvent              = $objEvent->row();
				$arrEvent['singleSRC'] = $objModel->path;
				
				$this->addImageToTemplate($objTemplate, $arrEvent);
			}
		}
		
		$objTemplate->enclosure = array();
		
		// Add enclosures
		if ($objEvent->addEnclosure) {
			$this->addEnclosuresToTemplate($objTemplate, $this->objEvent->row());
		}
		
		$this->Template->event = $objTemplate->parse();
		
		// HOOK: comments extension required
		if ($objEvent->noComments || !in_array('comments', \ModuleLoader::getActive())) {
			$this->Template->allowComments = false;
			
			return;
		}
		
		$this->Template->allowComments = $objCalendar->allowComments;
		
		// Comments are not allowed
		if (!$objCalendar->allowComments) {
			return;
		}
		
		// Adjust the comments headline level
		$intHl               = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);
		
		$this->import('Comments');
		$arrNotifies = array();
		
		// Notify the system administrator
		if ($objCalendar->notify != 'notify_author') {
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}
		
		// Notify the author
		if ($objCalendar->notify != 'notify_admin') {
			if (($objAuthor = $objEvent->getRelated('author')) !== null && $objAuthor->email != '') {
				$arrNotifies[] = $objAuthor->email;
			}
		}
		
		$objConfig = new \stdClass();
		
		$objConfig->perPage        = $objCalendar->perPage;
		$objConfig->order          = $objCalendar->sortOrder;
		$objConfig->template       = $this->com_template;
		$objConfig->requireLogin   = $objCalendar->requireLogin;
		$objConfig->disableCaptcha = $objCalendar->disableCaptcha;
		$objConfig->bbcode         = $objCalendar->bbcode;
		$objConfig->moderate       = $objCalendar->moderate;
		
		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_calendar_events', $objEvent->id, $arrNotifies);
	}
	
	protected function isSearchIndexer()
	{
		return (strpos($_SERVER['HTTP_REFERER'], 'main.php?act=index&do=maintenance') !== false);
	}
}
