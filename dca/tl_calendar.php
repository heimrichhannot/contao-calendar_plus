<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package extendes_events
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_calendar'];

/**
 * Operations
 */
array_insert($dc['list']['operations'], 2, array(
	'promoters' => array
	(
		'label' => &$GLOBALS['TL_LANG']['tl_calendar']['promoters'],
		'href'  => 'table=tl_calendar_promoters',
		'icon'  => 'system/modules/calendar_plus/assets/img/icons/promoters.png'
	)
));