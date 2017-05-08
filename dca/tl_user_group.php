<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package calendar_dav
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_user_group'];

/**
 * Add Permissions
 */
$dc['fields']['calendarp']['options'][] = 'subevents';
$dc['fields']['calendarp']['options'][] = 'promoters';
$dc['fields']['calendarp']['options'][] = 'docents';
$dc['fields']['calendarp']['options'][] = 'eventtypes';
$dc['fields']['calendarp']['options'][] = 'rooms';