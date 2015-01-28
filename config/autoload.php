<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package Calendar_plus
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'HeimrichHannot\CalendarPlus\CalendarPlusEventsModel' => 'system/modules/calendar_plus/models/CalendarPlusEventsModel.php',

	// Modules
	'HeimrichHannot\CalendarPlus\ModuleEventChooser'      => 'system/modules/calendar_plus/modules/ModuleEventChooser.php',

	// Widgets
	'HeimrichHannot\CalendarPlus\SubEventList'            => 'system/modules/calendar_plus/widgets/SubEventList.php',

	// Classes
	'HeimrichHannot\CalendarPlus\ExtendedEvents'          => 'system/modules/calendar_plus/classes/ExtendedEvents.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_event_chooser' => 'system/modules/calendar_plus/templates',
));
