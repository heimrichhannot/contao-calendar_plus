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

$arrLang = &$GLOBALS['TL_LANG']['tl_calendar'];

/**
 * Fields
 */
$arrLang['root'][0] = 'Zugehörigkeit';
$arrLang['root'][1] = 'Wählen Sie eine Zugehörigkeit des Archivs zu einem Startpunkt des Seitenbaumes aus.';

$arrLang['eventTypeArchives'][0] = 'Zusätzliche Veranstaltungsarten-Archive';
$arrLang['eventTypeArchives'][1] = 'Wählen Sie zusätzliche Veranstaltungsarten-Archive, aus welchem Sie Veranstaltungsarten in diesem Archiv bereitstellen möchten.';

$arrLang['addMemberDocentGroups'][0] = 'Dozenten aus Mitgliedergruppen hinzufügen';
$arrLang['addMemberDocentGroups'][1] = 'Fügen Sie Veranstaltungen echte Mitglieder aus den gewählten Mitgliedergruppen hinzu.';

$arrLang['memberDocentGroups'][0] = 'Erlaubte Mitgliedergruppen';
$arrLang['memberDocentGroups'][1] = 'Mitglieder dieser Mitgliedergruppen können Events als Dozenten hinzgefügt werden.';

$arrLang['uploadFolder'] = ['Upload-Verzeichnis', 'Geben Sie das Standard-Uploadverzeichnis hier an.'];


/**
 * Legends
 */
$arrLang['root_legend']   = 'Zugehörigkeit';
$arrLang['docent_legend'] = 'Dozenten';
$arrLang['files_legend']  = 'Dateien & Uploads';
$arrLang['join_legend']   = 'Verknüpfungen';


/**
 * Child Tables
 */
$arrLang['promoters'][0] = 'Veranstalter';
$arrLang['promoters'][1] = 'Veranstalter für Kalender ID %s anzeigen';

$arrLang['docents'][0] = 'Dozenten';
$arrLang['docents'][1] = 'Dozenten für Kalender ID %s anzeigen';

$arrLang['eventtypes'][0] = 'Veranstaltungsarten';
$arrLang['eventtypes'][1] = 'Veranstaltungsarten für Kalender ID %s anzeigen';

$arrLang['eventtypearchives'][0] = 'Veranstaltungsarchiv';
$arrLang['eventtypearchives'][1] = 'Veranstaltungsarchiv für Kalender ID %s anzeigen';

$arrLang['calendarroomarchives'][0] = 'Veranstaltunsräume anzeigen';
$arrLang['calendarroomarchives'][1] = 'Veranstaltunsräume für Kalender ID %s anzeigen';