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
// Allgemein
$GLOBALS['TL_LANG']['tl_calendar_room']['title'][0] = 'Bezeichnung';
$GLOBALS['TL_LANG']['tl_calendar_room']['title'][1] = 'Geben Sie die Bezeichnung des Raums ein';
$GLOBALS['TL_LANG']['tl_calendar_room']['shortTitle'][0] = 'Kurzbezeichnung';
$GLOBALS['TL_LANG']['tl_calendar_room']['shortTitle'][1] = 'Geben Sie die Kurzbezeichnung des Raums ein';
$GLOBALS['TL_LANG']['tl_calendar_room']['alias'][0] = 'Raumalias';
$GLOBALS['TL_LANG']['tl_calendar_room']['alias'][1] = 'Der Raumalias ist eine eindeutige Referenz, die anstelle der numerischen Raum-ID aufgerufen werden kann.';
// Adresse
$GLOBALS['TL_LANG']['tl_calendar_room']['street'][0] = 'Straße';
$GLOBALS['TL_LANG']['tl_calendar_room']['street'][1] = 'Geben Sie die Straße ein, in der sich der Raum befindet.';
$GLOBALS['TL_LANG']['tl_calendar_room']['postal'][0] = 'Postleitzahl';
$GLOBALS['TL_LANG']['tl_calendar_room']['postal'][1] = 'Geben Sie die Postleitzahl der Stadt ein, in der sich der Raum befindet.';
$GLOBALS['TL_LANG']['tl_calendar_room']['city'][0] = 'Stadt';
$GLOBALS['TL_LANG']['tl_calendar_room']['city'][1] = 'Geben Sie die Stadt ein, in der sich der Raum befindet.';
$GLOBALS['TL_LANG']['tl_calendar_room']['country'][0] = 'Land';
$GLOBALS['TL_LANG']['tl_calendar_room']['country'][1] = 'Geben Sie das Land ein, in dem sich der Raum befindet.';
$GLOBALS['TL_LANG']['tl_calendar_room']['singleCoords'][0] = 'Koordinaten';
$GLOBALS['TL_LANG']['tl_calendar_room']['singleCoords'][1] = 'Geben Sie hier die Koordinaten der Raum-Adresse in der Form "Latitude,Longitude" ein (ohne Anführungsstriche).';
// Raum
$GLOBALS['TL_LANG']['tl_calendar_room']['floor'][0] = 'Flur';
$GLOBALS['TL_LANG']['tl_calendar_room']['floor'][1] = 'Geben Sie den Flur ein, in dem sich der Raum befindet.';
$GLOBALS['TL_LANG']['tl_calendar_room']['isIndoor'][0] = 'Innenraum';
$GLOBALS['TL_LANG']['tl_calendar_room']['isIndoor'][1] = 'Der Raum befindet sich im Inneren.';
// Veröffentlichung
$GLOBALS['TL_LANG']['tl_calendar_room']['published'][0] = 'Raum veröffentlichen';
$GLOBALS['TL_LANG']['tl_calendar_room']['published'][1] = 'Den Raum auf der Webseite anzeigen.';
$GLOBALS['TL_LANG']['tl_calendar_room']['start'][0] = 'Anzeigen ab';
$GLOBALS['TL_LANG']['tl_calendar_room']['start'][1] = 'Den Raum erst ab diesem Tag auf der Webseite anzeigen.';
$GLOBALS['TL_LANG']['tl_calendar_room']['stop'][0] = 'Anzeigen bis';
$GLOBALS['TL_LANG']['tl_calendar_room']['stop'][1] = 'Den Raum nur bis zu diesem Tag auf der Webseite anzeigen.';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_room']['title_legend'] = 'Titel';
$GLOBALS['TL_LANG']['tl_calendar_room']['address_legend'] = 'Adressdaten';
$GLOBALS['TL_LANG']['tl_calendar_room']['room_legend'] = 'Raum';
$GLOBALS['TL_LANG']['tl_calendar_room']['publish_legend'] = 'Veröffentlichung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_room']['new'] = array('Neuer Eventraum', 'Eventraum erstellen');
$GLOBALS['TL_LANG']['tl_calendar_room']['edit'] = array('Eventraum bearbeiten', 'Eventraum ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar_room']['copy'] = array('Eventraum duplizieren', 'Eventraum ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar_room']['delete'] = array('Eventraum löschen', 'Eventraum ID %s löschen');
$GLOBALS['TL_LANG']['tl_calendar_room']['toggle'] = array('Eventraum veröffentlichen', 'Eventraum ID %s veröffentlichen/verstecken');
$GLOBALS['TL_LANG']['tl_calendar_room']['show'] = array('Eventraum Details', 'Eventraum-Details ID %s anzeigen');
