<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$dc = &$GLOBALS['TL_DCA']['tl_calendar_events'];

/**
 * Palettes
 */

$dc['palettes']['default'] = str_replace('title', 'title,shortTitle', $dc['palettes']['default']);
$dc['palettes']['default'] = str_replace('{details_legend}', '{details_legend},parentEvent', $dc['palettes']['default']);
$dc['palettes']['default'] = str_replace('location', 'location,locationAdditional,street,zipcode,city,coordinates,addMap', $dc['palettes']['default']);
$dc['palettes']['__selector__'] = array_merge($dc['palettes']['__selector__'], array('addMap'));
$dc['subpalettes']['addMap'] = 'map';

/**
 * Callbacks
 */

$dc['config']['onload_callback'][] = array('tl_extended_events_calendar_events', 'setDefaultParentEvent');

/**
 * Operations
 */

if (!isset($_GET['epid'])) {
	$dc['list']['operations']['showSubEvents'] = array
	(
		'button_callback'     => array('tl_extended_events_calendar_events', 'showSubEvents'),
		'icon' => '/system/modules/event_subscription/html/img/icons/show-sub-events.png'
	);
}

/**
 * Fields
 */

$dc['fields']['shortTitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['shortTitle'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['parentEvent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['parentEvent'],
	'inputType'               => 'select',
	'exclude'                 => true,
	'foreignKey'			  => 'tl_calendar_events.title',
	'eval'					  => array('tl_class' => 'long', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true),
	'wizard' => array
	(
		array('tl_extended_events_calendar_events', 'editParentEvent')
	),
	'sql'                     => "int(16) NOT NULL",
);

$dc['fields']['location']['eval']['tl_class'] = 'w50 clr';

$dc['fields']['locationAdditional'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['locationAdditional'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['street'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['street'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['zipcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['zipcode'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['city'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['city'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['coordinates'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['coordinates'],
	'inputType'               => 'text',
	'exclude'                 => true,
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$dc['fields']['addMap'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addMap'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange' => true, 'doNotCopy' => true, 'tl_class' => 'long clr'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$dc['fields']['map'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['map'],
	'inputType'               => 'fileTree',
	'exclude'                 => true,
	'eval'					  => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class' => 'long'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

class tl_extended_events_calendar_events extends Backend {

	public function editParentEvent(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
	}

	public function showSubEvents($row, $href, $label, $title, $icon, $attributes) {
		return '<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;pid=' . $row['pid'] . '&amp;epid=' . $row['id'] . '" title="Untergeordnete Events anzeigen">' . $this->generateImage($icon, $label) . '</a>';
	}
	
	public function setDefaultParentEvent($dc)
	{
		if (isset($_GET['id'])) {
			$objEvent = \CalendarEventsModel::findByPk($_GET['id']);
			$objParentEvent = \CalendarEventsModel::findByPk($_GET['epid']);
			if ($objEvent !== null && !$objEvent->parentEvent && isset($_GET['epid'])) {
				if (!$objEvent->pid)
					$objEvent->pid = $objParentEvent->pid;
				$objEvent->parentEvent = $_GET['epid'];
				$objEvent->save();
			}
		}
	}
}