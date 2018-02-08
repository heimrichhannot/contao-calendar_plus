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

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['hideSubEvents']                        =
    ['Untergeordnete Events verstecken', 'Es werden nur noch Events der höchsten Hierarchieebene angezeigt (Root-Events).'];
$GLOBALS['TL_LANG']['tl_module']['cal_ungroupSubevents']                 =
    ['Untergeordnete Events nicht gruppieren', 'Stellt untergeordnete Events als einzelne Veranstaltung dar, ohne Verknüpfung zum Elterevent.'];
$GLOBALS['TL_LANG']['tl_module']['cal_filterModule']                     = ['Eventfilter', 'Legen Sie den Filter für die Anzeige der Events fest.'];
$GLOBALS['TL_LANG']['tl_module']['cal_templateSubevent']                 = [
    'Untergeordnete Events-Template',
    'Hier können Sie das Event-Template für untergeordnete Events auswählen, wird nur verwendet wenn untergeordnete Events mit ihren Elternevents gruppiert ausgegeben werden.',
];
$GLOBALS['TL_LANG']['tl_module']['cal_template_modal']                   =
    ['Event-Template (Modal)', 'Hier können Sie das Event-Template für die Anzeige im Modalfenster auswählen.'];
$GLOBALS['TL_LANG']['tl_module']['cal_showInModal']                      =
    ['Eventdetails im Modalfenster anzeigen <span style="color: #c33">(Veraltet, verwenden Sie "Elemente im Modalfenstern anzeigen")</span>', '<strong>Achtung</strong>: Der Eventleser muss entsprechend angegeben werden.'];
$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchive']         = ['Eventtyp-Archive zusammenfassen', 'Fasst alle Archive zusammen'];
$GLOBALS['TL_LANG']['tl_module']['cal_combineEventTypesArchiveMultiple'] = ['Mehrfachauswahl für zusammengefasste Archive', 'Lässt die Suche nach mehreren Eventtypen zu.'];
$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchive']                = ['Eventtyp-Archive auswählen', 'Aus welchen Eventtyp-Archiven sollen die Eventtypen bezogen werden?'];
$GLOBALS['TL_LANG']['tl_module']['cal_eventTypesArchiveMultiple']        =
    ['Mehrfachauswahl für Eventtyp-Archive', 'In aktivierten Archiven ist eine Mehrfachauswahl der Eventtypen möglich.'];
$GLOBALS['TL_LANG']['tl_module']['cal_restrictedValueFields']            = ['Ergebnisse eingrenzen (Feldoptionen)', 'Die Veranstaltungen anhand der Optionswerte eingrenzen.'];
$GLOBALS['TL_LANG']['tl_module']['cal_subeventDocentTemplate']           =
    ['Mitglieder-Dozenten-Template für untergeordnete Events', 'Hier können Sie ein individuelles Template zur Anzeige des Dozenten für untergeordnete Veranstaltungen auswählen.'];
$GLOBALS['TL_LANG']['tl_module']['cal_subeventHostTemplate']             = [
    'Mitglieder-Moratoren-Template für untergeordnete Events',
    'Hier können Sie ein individuelles Template zur Anzeige des Moderators für untergeordnete Veranstaltungen auswählen.',
];
$GLOBALS['TL_LANG']['tl_module']['cal_filterRelatedOnEmpty']             = [
    'Verwandte Veranstaltungen bei keinen Ergebnissen anzeigen',
    'Wenn keine Veranstaltungen mit den Filtereinstellungen gefunden werden, wird die Liste mit verwandten Veranstaltungen zurückgeliefert.',
];
$GLOBALS['TL_LANG']['tl_module']['cal_addKeywordSearch']                 =
    ['Stichwortsuche aktivieren', 'Ein Suchfeld für die Stichwortsuche über Titel und Teaser von Veranstaltungen hinzufügen.'];
$GLOBALS['TL_LANG']['tl_module']['cal_docent_combine']                   =
    ['Moderatoren mit Referenten zusammenfassen', 'Die Moderatoren in der Liste der Referenten zusammenfassen und gruppiert ausgeben.'];
$GLOBALS['TL_LANG']['tl_module']['cal_useInfiniteScroll']                = ['InifiniteScroll nutzen', ''];
$GLOBALS['TL_LANG']['tl_module']['cal_useAutoTrigger']                   =
    ['AutoTrigger aktivieren', 'Wenn aktiviert wird Liste automatisch beim Scrollen nachgeladen. Andernfalls erfolgt das Nachladen mittels Button'];
$GLOBALS['TL_LANG']['tl_module']['cal_changeTriggerText']                = ['Triggertext ändern', 'Ermöglicht die Individualisierung des Triggertextes'];
$GLOBALS['TL_LANG']['tl_module']['cal_triggerText']                      = ['Triggertext', 'Tragen Sie hier den individiuellen Triggertext ein.'];
$GLOBALS['TL_LANG']['tl_module']['cal_promoterTypes']                    = ['Veranstaltertypen', 'Wählen sie Veranstaltertypen aus.'];
$GLOBALS['TL_LANG']['tl_module']['cal_promoters']                        = ['Veranstalter', 'Wählen sie Veranstalter aus.'];
$GLOBALS['TL_LANG']['tl_module']['cal_promoterTemplate']                 = ['Veranstalter-Template', 'Hier können Sie das Veranstalter-Template überschreiben.'];
$GLOBALS['TL_LANG']['tl_module']['cal_alwaysShowParents']                = ['Immer Eltern-Events ausgeben', 'Funktioniert nur wenn "Untergeordnete Events nicht gruppieren" deaktiviert ist. Führt die Filterung zu einem Treffer bei einem Kind-Event, wird stattdessen das Elternelement ausgegeben.'];


/**
 * Legends
 */

$GLOBALS['TL_LANG']['tl_module']['memberdocent_legend'] = 'Mitglieder-Dozenten';
$GLOBALS['TL_LANG']['tl_module']['eventtype_legend']    = 'Veranstaltungsarten';
$GLOBALS['TL_LANG']['tl_module']['related_legend']      = 'Verwandte Veranstaltungen';
$GLOBALS['TL_LANG']['tl_module']['keyword_legend']      = 'Stichwortsuche';
$GLOBALS['TL_LANG']['tl_module']['restrict_legend']     = 'Eingrenzung';
$GLOBALS['TL_LANG']['tl_module']['docent_legend']       = 'Dozenten-Einstellungen';
