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
$GLOBALS['TL_LANG']['tl_calendar_promoters']['type']         = ['Typ', 'Geben Sie hier den Veranstalter-Typ an'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['title']        = ['Titel', 'Bitte geben Sie einen Titel an.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['alias']        =
    ['Veranstalteralias', 'Der Veranstalteralias ist eine eindeutige Referenz, die anstelle der numerischen Veranstalter-ID aufgerufen werden kann.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['subtitle']     = ['Unterüberschrift', 'Hier können Sie eine Unterüberschrift eingeben.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['teaser']       = ['Teasertext', 'Hier können Sie einen Teasertext eingeben.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['company']      = ['Firma', 'Hier können Sie einen Firmennamen eingeben.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['street']       = ['Straße', 'Bitte geben Sie den Straßennamen und die Hausnummer ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['postal']       = ['Postleitzahl', 'Bitte geben Sie die Postleitzahl ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['city']         = ['Ort', 'Bitte geben Sie den Namen des Ortes ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['country']      = ['Land', 'Bitte wählen Sie ein Land.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['singleCoords'] =
    ['Geo-Koordinaten', 'Bitte geben Sie die Geo-Koordination (Lat/Lng) der Adresse an. Sofern leer gelassen, wir die Adresse automatisch aus Strasse, PLZ und Ort bestimmt.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['contactName']  = ['Ansprechpartner', 'Bitte geben Sie den Namen des Ansprechpartners ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['phone']        = ['Telefonnummer', 'Bitte geben Sie die Telefonnummer ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['fax']          = ['Faxnummer', 'Bitte geben Sie die Faxnummer ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['email']        = ['E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['room']         = ['Raum', 'Bitte wählen Sie einen Raum für diesen Veranstalter aus.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['website']      =
    ['Webseite', 'Der Veranstalteralias ist eine eindeutige Referenz, die anstelle der numerischen Veranstalter-ID aufgerufen werden kann.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['logo']         = ['Logo', 'Laden Sie hier ein Logo für den Veranstalter hoch.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['published']    = ['Veranstalter veröffentlichen', 'Den Veranstalter auf der Webseite anzeigen.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['start']        = ['Anzeigen ab', 'Den Veranstalter erst ab diesem Tag auf der Webseite anzeigen.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['stop']         = ['Anzeigen bis', 'Den Veranstalter nur bis zu diesem Tag auf der Webseite anzeigen.'];

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_promoters']['title_legend']   = 'Titel';
$GLOBALS['TL_LANG']['tl_calendar_promoters']['teaser_legend']  = 'Unterüberschrift und Teaser';
$GLOBALS['TL_LANG']['tl_calendar_promoters']['address_legend'] = 'Adressdaten';
$GLOBALS['TL_LANG']['tl_calendar_promoters']['contact_legend'] = 'Kontaktdaten';
$GLOBALS['TL_LANG']['tl_calendar_promoters']['publish_legend'] = 'Veröffentlichung';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_promoters']['new']    = ['Neuer Veranstalter', 'Einen neuen Veranstalter erstellen.'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['show']   = ['Veranstalterdetails', 'Details des Veranstalter ID %s anzeigen'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['edit']   = ['Veranstalter bearbeiten ', 'Veranstalter ID %s bearbeiten'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['cut']    = ['Veranstalter verschieben', 'Verschieben des Veranstalters ID %s'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['copy']   = ['Veranstalter kopieren ', 'Kopieren des Veranstalters ID %s'];
$GLOBALS['TL_LANG']['tl_calendar_promoters']['delete'] = ['Veranstalter löschen', 'Löschen des Veranstalters ID %s'];

/**
 * Wizard
 */
$GLOBALS['TL_LANG']['tl_calendar_promoters']['editroom'] = ['Veranstaltunsraum bearbeiten', 'Den Veranstaltunsraum ID %s bearbeiten'];

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_promoters']['type']['default'] = 'Standard';