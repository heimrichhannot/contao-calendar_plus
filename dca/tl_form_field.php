<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */
$dc = &$GLOBALS['TL_DCA']['tl_form_field'];

// add palette for field type nl_subscribe
if (is_array($dc['palettes']))
{
	array_insert($dc['palettes'], count($dc['palettes']),
	array('subEventList' => '{type_legend},type,name,label;{fconfig_legend},event,widgetType,mandatory;{options_legend},options;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}

/**
 * Callbacks
 */

$dc['config']['onload_callback'][] = array('tl_extended_events_form_field', 'saveOldState');
$dc['config']['onsubmit_callback'][] = array('tl_extended_events_form_field', 'createOptions');

/**
 * Fields
 */

$dc['fields']['event'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['event'],
	'inputType'               => 'select',
	'foreignKey'			  => 'tl_calendar_events.title',
	'eval'					  => array('tl_class' => 'long', 'submitOnChange' => true, 'chosen' => true, 'mandatory' => true),
	'wizard' => array
	(
		array('tl_extended_events_form_field', 'editEvent')
	),
	'options_callback'		  => array('tl_extended_events_form_field', 'getParentalEvents'),
	'sql'                     => "int(16) NOT NULL",
);

$dc['fields']['widgetType'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['widgetType'],
	'inputType'               => 'select',
	'eval'					  => array('tl_class' => 'w50', 'mandatory' => true),
	'options'				  => array('radio' => 'Radio-Button-Menü', 'checkbox' => 'Checkbox-Menü'),
	'reference'				  => &$GLOBALS['TL_LANG']['tl_form_field'],
	'sql'                     => "varchar(255) NOT NULL default ''",
);

class tl_extended_events_form_field extends Backend {

	public function editEvent(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
	}
	
	public function saveOldState(&$dc)
	{
		$objFormField = \FormFieldModel::findByPk($this->Input->get('id'));
		if ($objFormField === null)
			return;
		
		// set default event and options for usability reasons
		if (!$objFormField->event && $objFormField->type == 'subEventList')
		{
			$parentalEvents = $this->getParentalEvents();
			if (!empty($parentalEvents))
			{
				$parentalEventsKeys = array_keys($parentalEvents);
				$objFormField->event = $parentalEventsKeys[0];
				static::doCreateOptions($this->Input->get('id'), $parentalEventsKeys[0]); // contains save()
			}
		}
		$this->Session->set('tl_form_field.event', $objFormField->event);
	}

	public function getParentalEvents() {
		$objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findPublishedParentalEvents();
		if ($objEvents !== null)
		{
			$parentalEvents = array();
			while ($objEvents->next())
			{
				$parentalEvents[$objEvents->id] = $objEvents->shortTitle ? $objEvents->shortTitle : $objEvents->title;
			}
			return $parentalEvents;
		}
	}
	
	public function createOptions(DataContainer &$dc)
	{
		// only recreate the options, if the new selection differs from the old one
		if ($this->Session->get('tl_form_field.event') != $dc->activeRecord->event)
		{
			static::doCreateOptions($dc->activeRecord->id, $dc->activeRecord->event);
			$this->Session->remove('tl_form_field.event');
		}
	}
	
	public static function doCreateOptions($formFieldId, $formFieldEventId)
	{
		$objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findPublishedSubEvents($formFieldEventId);
		
		if ($objEvents !== null)
		{
			$options = array();
			while ($objEvents->next()) {
				$options[] = array
				(
					'label' => $objEvents->shortTitle ? $objEvents->shortTitle : $objEvents->title,
					'value' => specialchars($objEvents->id)
				);
			}
			$objFormField = \FormFieldModel::findByPk($formFieldId);
			$objFormField->event = $formFieldEventId;
			$objFormField->options = serialize($options);
			$objFormField->save();
		}
	}

}