<?php

$dc = &$GLOBALS['TL_DCA']['tl_calendar_events'];

/**
 * Palettes
 */

$dc['palettes']['default']      = str_replace('title', 'title,shortTitle', $dc['palettes']['default']);
$dc['palettes']['default']      = str_replace('{details_legend}', '{details_legend},parentEvent,promoter,docents', $dc['palettes']['default']);
$dc['palettes']['default']      = str_replace('location', 'location,locationAdditional,street,zipcode,city,coordinates,addMap', $dc['palettes']['default']);
$dc['palettes']['__selector__'] = array_merge($dc['palettes']['__selector__'], array('addMap'));
$dc['subpalettes']['addMap']    = 'map,mapText';

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
		'button_callback' => array('tl_extended_events_calendar_events', 'showSubEvents'),
		'icon'            => '/system/modules/calendar_plus/assets/img/icons/show-sub-events.png'
	);
}

/**
 * Fields
 */

$dc['fields']['shortTitle'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['shortTitle'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['parentEvent'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['parentEvent'],
	'inputType'  => 'select',
	'exclude'    => true,
	'foreignKey' => 'tl_calendar_events.title',
	'eval'       => array('tl_class' => 'long', 'includeBlankOption' => true, 'submitOnChange' => true, 'chosen' => true),
	'wizard'     => array
	(
		array('tl_extended_events_calendar_events', 'editParentEvent')
	),
	'sql'        => "int(16) NOT NULL",
);

$dc['fields']['location']['eval']['tl_class'] = 'w50 clr';

$dc['fields']['locationAdditional'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['locationAdditional'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['street'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['street'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['zipcode'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['zipcode'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['city'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['city'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['coordinates'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['coordinates'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);

$dc['fields']['addMap'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['addMap'],
	'exclude'   => true,
	'inputType' => 'checkbox',
	'eval'      => array('submitOnChange' => true, 'doNotCopy' => true, 'tl_class' => 'long clr'),
	'sql'       => "char(1) NOT NULL default ''"
);

$dc['fields']['map'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['map'],
	'inputType' => 'fileTree',
	'exclude'   => true,
	'eval'      => array('filesOnly' => true, 'extensions' => Config::get('validImageTypes'), 'fieldType' => 'radio', 'mandatory' => true, 'tl_class' => 'long'),
	'sql'       => "binary(16) NULL"
);

$dc['fields']['mapText'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['mapText'],
	'inputType' => 'text',
	'exclude'   => true,
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "varchar(255) NOT NULL default ''"
);


$dc['fields']['promoter'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['promoter'],
	'inputType'  => 'select',
	'foreignKey' => 'tl_calendar_promoters.title',
	'exclude'    => true,
	'eval'       => array('chosen' => true, 'includeBlankOption' => true, 'submitOnChange' => true, 'tl_class' => 'long'),
	'wizard'     => array
	(
		array('tl_extended_events_calendar_events', 'editPromoter')
	),
	'sql'        => "int(10) unsigned NOT NULL default '0'"
);

$dc['fields']['docents'] = array
(
	'label'      => &$GLOBALS['TL_LANG']['tl_calendar_events']['docents'],
	'exclude'    => true,
	'search'     => true,
	'inputType'  => 'select',
	'foreignKey' => 'tl_calendar_docents.title',
	'eval'       => array('mandatory' => true, 'multiple' => true, 'chosen' => true, 'tl_class' => 'clr', 'style' => 'width: 853px'),
	'sql'        => "blob NULL",
	'relation'   => array('type' => 'hasMany', 'load' => 'lazy')
);

/**
 * Filter list / show subevents
 */
if ($_GET['table'] == 'tl_calendar_events') {
	$objDatabase = \Database::getInstance();

	if (isset($_GET['epid'])) {
		if (($objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent($_GET['epid'])) !== null) {
			while ($objEvents->next()) {
				$dc['list']['sorting']['root'][] = $objEvents->id;
			}
		} else {
			$dc['list']['sorting']['root'] = array(-1); // don't display anything
		}
	} else {
		if (($objEvents = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByParentEvent(0)) !== null) {
			while ($objEvents->next()) {
				$dc['list']['sorting']['root'][] = $objEvents->id;
			}
		}
	}
}

class tl_extended_events_calendar_events extends Backend
{
	public function editPromoter(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_promoters&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][1]), $dc->value) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_calendar_events']['editpromoter'][0], 'style="vertical-align:top"') . '</a>';
	}

	public function editParentEvent(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(specialchars($GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1]), $dc->value) . '" style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.gif', $GLOBALS['TL_LANG']['tl_calendar_events']['editparentevent'][0], 'style="vertical-align:top"') . '</a>';
	}

	public function showSubEvents($row, $href, $label, $title, $icon, $attributes)
	{
		return '<a href="contao/main.php?do=calendar&amp;table=tl_calendar_events&amp;pid=' . $row['pid'] . '&amp;epid=' . $row['id'] . '" title="Untergeordnete Events anzeigen">' . \Image::getHtml($icon, $label) . '</a>';
	}

	public function setDefaultParentEvent($dc)
	{
		if (isset($_GET['id'])) {
			$objEvent       = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByPk($_GET['id']);
			$objParentEvent = HeimrichHannot\CalendarPlus\CalendarPlusEventsModel::findByPk($_GET['epid']);
			if ($objEvent !== null && !$objEvent->parentEvent && isset($_GET['epid'])) {
				if (!$objEvent->pid)
					$objEvent->pid = $objParentEvent->pid;
				$objEvent->parentEvent = $_GET['epid'];
				$objEvent->save();
			}
		}
	}
}