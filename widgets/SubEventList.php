<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */


/**
 * Class SubEventList
 *
 * Provide methods to handle radio buttons.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class SubEventList extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget_rdo';
	
	protected static $defaultValueSet = false;


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}
	
	public function getOptions()
	{
		return $this->arrOptions;
	}


	/**
	 * Check for a valid option (see #4383)
	 */
	public function validate()
	{
		$varValue = deserialize($this->getPost($this->strName));
		
		// validation deactivated for editing reasons
// 		if ($varValue != '' && !$this->isValidOption($varValue))
// 		{
// 			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], $varValue));
// 		}

		parent::validate();
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		switch($this->widgetType) {
			case 'radio':
				$arrOptions = array();
		
				foreach ($this->arrOptions as $i => $arrOption)
				{
					// further info link
					if (\CalendarEventsModel::hasTextOrTeaser($arrOption['value']))
						$arrOption['url'] = $this->replaceInsertTags('{{event_url::' . $arrOption['value'] . '}}');
				
					// places left
					if (in_array('event_subscription', $this->Config->getActiveModules())) {
						$this->import('Database');
						$objSubEvent = \CalendarEventsModel::findByPk($arrOption['value']);
						if ($objSubEvent !== null) {
							$objParentEvent = \CalendarEventsModel::findByPk($objSubEvent->parentEvent);
							if ($objParentEvent !== null && $objSubEvent->addSubscription && !$objParentEvent->hidePlacesLeftSubEvents) {
								$placesLeftSubEvent = \CalendarEventsModel::getPlacesLeftSubEvent($objSubEvent->id, $this->Database);
								$arrOption['placesLeft'] = sprintf($GLOBALS['TL_LANG']['tl_event_subscription'][$placesLeftSubEvent <= 0 ? 'noPlacesLeftSubEventTeaser' : 'placesLeftSubEventTeaser'],
										$placesLeftSubEvent, $objSubEvent->placesTotal);
								if ($placesLeftSubEvent <= 0)
									$arrOption['disabled'] = true;
							}
						}
					}
					
					$arrOptions[] = $this->generateRadioButton($arrOption, $i);
				}
				
				// Add a "no entries found" message if there are no options
				if (empty($arrOptions))
				{
					$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
				}
				
				return sprintf('<fieldset id="ctrl_%s" class="tl_radio_container%s"><legend>%s%s%s%s</legend>%s</fieldset>%s',
								$this->strId,
								(($this->strClass != '') ? ' ' . $this->strClass : ''),
								($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
								$this->strLabel,
								($this->required ? '<span class="mandatory">*</span>' : ''),
								$this->xlabel,
								implode('<br>', $arrOptions),
								$this->wizard);
				break;
			case 'checkbox':
				$arrOptions = array();
				
				$this->multiple = true;
				$this->mandatory = false;

				if (!$this->multiple && count($this->arrOptions) > 1)
				{
					$this->arrOptions = array($this->arrOptions[0]);
				}
		
				// The "required" attribute only makes sense for single checkboxes
				if (!$this->multiple && $this->mandatory)
				{
					$this->arrAttributes['required'] = 'required';
				}
		
				$state = $this->Session->get('checkbox_groups');
		
				// Toggle the checkbox group
				if (\Input::get('cbc'))
				{
					$state[\Input::get('cbc')] = (isset($state[\Input::get('cbc')]) && $state[\Input::get('cbc')] == 1) ? 0 : 1;
					$this->Session->set('checkbox_groups', $state);
		
					$this->redirect(preg_replace('/(&(amp;)?|\?)cbc=[^& ]*/i', '', \Environment::get('request')));
				}
		
				$blnFirst = true;
				$blnCheckAll = false;
				
				foreach ($this->arrOptions as $i=>$arrOption)
				{
					// further info link
					if (\CalendarEventsModel::hasTextOrTeaser($arrOption['value']))
						$arrOption['url'] = $this->replaceInsertTags('{{event_url::' . $arrOption['value'] . '}}');
					
					// places left
					if (in_array('event_subscription', $this->Config->getActiveModules())) {
						$this->import('Database');
						$objSubEvent = \CalendarEventsModel::findByPk($arrOption['value']);
						if ($objSubEvent !== null) {
							$objParentEvent = \CalendarEventsModel::findByPk($objSubEvent->parentEvent);
							if ($objParentEvent !== null && $objSubEvent->addSubscription && !$objParentEvent->hidePlacesLeftSubEvents) {
								$placesLeftSubEvent = \CalendarEventsModel::getPlacesLeftSubEvent($objSubEvent->id, $this->Database);
								$arrOption['placesLeft'] = sprintf($GLOBALS['TL_LANG']['tl_event_subscription'][$placesLeftSubEvent <= 0 ? 'noPlacesLeftSubEventTeaser' : 'placesLeftSubEventTeaser'],
										$placesLeftSubEvent, $objSubEvent->placesTotal);
								
								if ($placesLeftSubEvent <= 0)
									$arrOption['disabled'] = true;
							}
						}
					}
					
					// Single dimension array
					if (is_numeric($i))
					{
						$arrOptions[] = $this->generateCheckbox($arrOption, $i);
						continue;
					}
		
					$id = 'cbc_' . $this->strId . '_' . standardize($i);
		
					$img = 'folPlus';
					$display = 'none';
		
					if (!isset($state[$id]) || !empty($state[$id]))
					{
						$img = 'folMinus';
						$display = 'block';
					}
		
					$arrOptions[] = '<div class="checkbox_toggler' . ($blnFirst ? '_first' : '') . '"><a href="' . $this->addToUrl('cbc=' . $id) . '" onclick="AjaxRequest.toggleCheckboxGroup(this,\'' . $id . '\');Backend.getScrollOffset();return false"><img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/' . $img . '.gif" width="18" height="18" alt="toggle checkbox group"></a>' . $i .	'</div><fieldset id="' . $id . '" class="tl_checkbox_container checkbox_options" style="display:' . $display . '"><input type="checkbox" id="check_all_' . $id . '" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this, \'' . $id . '\')"> <label for="check_all_' . $id . '" style="color:#a6a6a6"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label>';
		
					// Multidimensional array
					foreach ($arrOption as $k=>$v)
					{
						$arrOptions[] = $this->generateCheckbox($v, $i.'_'.$k);
					}
		
					$arrOptions[] = '</fieldset>';
					$blnFirst = false;
					$blnCheckAll = false;
				}
		
				// Add a "no entries found" message if there are no options
				if (empty($arrOptions))
				{
					$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
					$blnCheckAll = false;
				}
		
				if ($this->multiple)
				{
					return sprintf('<fieldset id="ctrl_%s" class="tl_checkbox_container%s"><legend>%s%s%s%s</legend><input type="hidden" name="%s" value="">%s%s</fieldset>%s',
									$this->strId,
									(($this->strClass != '') ? ' ' . $this->strClass : ''),
									($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
									$this->strLabel,
									($this->required ? '<span class="mandatory">*</span>' : ''),
									$this->xlabel,
									$this->strName,
									($blnCheckAll ? '<input type="checkbox" id="check_all_' . $this->strId . '" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this,\'ctrl_' . $this->strId . '\')"> <label for="check_all_' . $this->strId . '" style="color:#a6a6a6"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label><br>' : ''),
									str_replace('<br></fieldset><br>', '</fieldset>', implode('<br>', $arrOptions)),
									$this->wizard);
				}
				else
				{
					return sprintf('<div id="ctrl_%s" class="tl_checkbox_single_container%s"><input type="hidden" name="%s" value="">%s</div>%s',
									$this->strId,
									(($this->strClass != '') ? ' ' . $this->strClass : ''),
									$this->strName,
									str_replace('<br></div><br>', '</div>', implode('<br>', $arrOptions)),
									$this->wizard);
				}
				break;
			default:
				// Form data rendering
				$result = sprintf('<fieldset id="ctrl_subevent" class="tl_checkbox_container"><h3>%s</h3>', $this->generateLabel());
				if (@unserialize($this->activeRecord->subevent) !== false)
				{
					$subEvents = deserialize($this->activeRecord->subevent, true);
					if ($_GET['act'] == 'edit') {
						$objSignupForm = \FormModel::findBy('alias', str_replace('fd_', '', $_GET['do']));
						if ($objSignupForm !== null) {
							$objEvent = \CalendarEventsModel::findBy('signupForm', $objSignupForm->id);
							$objSubEvents = \CalendarEventsModel::findPublishedSubEventsByParentEventId($objEvent->id);
							if ($objSubEvents !== null) {
								$i = 0;
								while ($objSubEvents->next()) {
									$result .= '<input type="checkbox" name="subevent[]" id="opt_subevent_' . $i . '" class="tl_checkbox" value="' . $objSubEvents->id . '" ' .
											(in_array($objSubEvents->id, $subEvents) ? 'checked="checked"' : '') . 'onfocus="Backend.getScrollOffset()">' .
											'<label for="opt_subevent_' . $i++ . '">' . ($objSubEvents->shortTitle ? $objSubEvents->shortTitle : $objSubEvents->title) . '</label><br>';
								}
							}
						}
					}
				}
				else {
					if ($_GET['act'] == 'edit') {
						$objSignupForm = \FormModel::findBy('alias', str_replace('fd_', '', $_GET['do']));
						if ($objSignupForm !== null) {
							$objEvent = \CalendarEventsModel::findBy('signupForm', $objSignupForm->id);
							$objSubEvents = \CalendarEventsModel::findPublishedSubEventsByParentEventId($objEvent->id);
							if ($objSubEvents !== null) {
								$i = 0;
								while ($objSubEvents->next()) {
									$result .= '<input type="radio" name="subevent[]" id="opt_subevent_' . $i . '" class="tl_checkbox" value="' . $objSubEvents->id . '" ' .
											($objSubEvents->id == $this->activeRecord->subevent ? 'checked="checked"' : '') . 'onfocus="Backend.getScrollOffset()">' .
											'<label for="opt_subevent_' . $i++ . '">' . ($objSubEvents->shortTitle ? $objSubEvents->shortTitle : $objSubEvents->title) . '</label><br>';
								}
							} else
								$result .= '<input name="subevent" value="' . $this->activeRecord->subevent . '">';
						}
					}
				}
				
				return $result . '</fieldset>';
				break;
		}
	}
	
	/**
	 * Generate a checkbox and return it as string
	 * @param array
	 * @param integer
	 * @return string
	 */
	protected function generateRadioButton($arrOption, $i)
	{
		return sprintf('<input type="radio" name="%s" id="opt_%s" class="tl_radio" value="%s"%s%s%s onfocus="Backend.getScrollOffset()"> <label for="opt_%s">%s%s%s</label>',
						$this->strName,
						$this->strId.'_'.$i,
						specialchars($arrOption['value']),
						$this->isChecked($arrOption),
						$this->getAttributes(),
						$arrOption['disabled'] == true ? ' disabled="disabled"' : '',
						$this->strId.'_'.$i,
						$arrOption['placesLeft'],
						$arrOption['label'],
						isset($arrOption['url']) ? ' (<a target="_blank" class="further-info" href="' . $arrOption['url'] . '">' .
							$GLOBALS['TL_LANG']['tl_form_field']['further-info'] . '</a>)' : '');
	}

	/**
	 * Generate a checkbox and return it as string
	 * @param array
	 * @param integer
	 * @return string
	 */
	protected function generateCheckbox($arrOption, $i)
	{
		return sprintf('<input type="checkbox" name="%s" id="opt_%s" class="tl_checkbox" value="%s"%s%s%s onfocus="Backend.getScrollOffset()"> <label for="opt_%s">%s%s%s</label>',
						$this->strName . ($this->multiple ? '[]' : ''),
						$this->strId.'_'.$i,
						($this->multiple ? specialchars($arrOption['value']) : 1),
						$this->isChecked($arrOption),
						$this->getAttributes(),
						$arrOption['disabled'] == true ? ' disabled="disabled"' : '',
						$this->strId.'_'.$i,
						$arrOption['placesLeft'],
						$arrOption['label'],
						isset($arrOption['url']) ? ' (<a target="_blank" class="further-info" href="' . $arrOption['url'] . '">' .
							$GLOBALS['TL_LANG']['tl_form_field']['further-info'] . '</a>)' : '');
	}
	
	/**
	 * Check whether an option is checked
	 *
	 * @param array $arrOption The options array
	 *
	 * @return string The "checked" attribute or an empty string
	 */
	protected function isChecked($arrOption)
	{
		if (empty($this->varValue) && !$arrOption['disabled'] && !static::$defaultValueSet)
		{
			static::$defaultValueSet = true;
			return static::optionChecked(1, 1);
		}

		return static::optionChecked($arrOption['value'], $this->varValue);
	}
}
