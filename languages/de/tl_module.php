<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['hideSubEvents'] = array('Untergeordnete Events verstecken', 'Es werden nur noch Events der höchsten Hierarchieebene angezeigt (Root-Events).');
$GLOBALS['TL_LANG']['tl_module']['cal_ungroupSubevents'] = array('Untergeordnete Events nicht gruppieren', 'Stellt untergeordnete Events als einzelne Veranstaltung dar, ohne Verknüpfung zum Elterevent.');
$GLOBALS['TL_LANG']['tl_module']['cal_filterModule'] = array('Eventfilter', 'Legen Sie den Filter für die Anzeige der Events fest.');
$GLOBALS['TL_LANG']['tl_module']['cal_templateSubevent'] = array('Untergeordnete Events-Template', 'Hier können Sie das Event-Template für untergeordnete Events auswählen, wird nur verwendet wenn untergeordnete Events mit ihren Elternevents gruppiert ausgegeben werden.');
$GLOBALS['TL_LANG']['tl_module']['cal_template_modal'] = array('Event-Template (Modal)', 'Hier können Sie das Event-Template für die Anzeige im Modalfenster auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_showInModal'] = array('Eventdetails im Modalfenster anzeigen', '<strong>Achtung</strong>: Der Eventleser muss entsprechend angegeben werden.');
$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchive'] = array('Eventtyp-Archive zusammenfassen', 'Fasst alle Archive zusammen');
$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchiveMultiple'] = array('Mehrfachauswahl für zusammengefasste Archive', 'Lässt die Suche nach mehreren Eventtypen zu.');
$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchive'] = array('Eventtyp-Archive auswählen', 'Aus welchen Eventtyp-Archiven sollen die Eventtypen bezogen werden?');
$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchiveMultiple'] = array('Mehrfachauswahl für Eventtyp-Archive', 'In aktivierten Archiven ist eine Mehrfachauswahl der Eventtypen möglich.');
$GLOBALS['TL_LANG']['tl_module']['cal_restrictedValueFields'] = array('Ergebnisse eingrenzen (Feldoptionen)', 'Die Veranstaltungen anhand der Optionswerte eingrenzen.');
$GLOBALS['TL_LANG']['tl_module']['cal_subeventDocentTemplate'] = array('Mitglieder-Dozenten-Template für untergeordnete Events', 'Hier können Sie ein individuelles Template zur Anzeige des Dozenten für untergeordnete Veranstaltungen auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_subeventHostTemplate'] = array('Mitglieder-Moratoren-Template für untergeordnete Events', 'Hier können Sie ein individuelles Template zur Anzeige des Moderators für untergeordnete Veranstaltungen auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_filterRelatedOnEmpty'] = array('Verwandte Veranstaltungen bei keinen Ergebnissen anzeigen', 'Wenn keine Veranstaltungen mit den Filtereinstellungen gefunden werden, wird die Liste mit verwandten Veranstaltungen zurückgeliefert.');

/**
 * Legends
 */

$GLOBALS['TL_LANG']['tl_module']['memberdocent_legend'] = 'Mitglieder-Dozenten';