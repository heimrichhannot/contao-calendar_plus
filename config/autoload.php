<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Extended_events
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
	// Classes
	'ExtendedEvents'                     => 'system/modules/extended_events/classes/ExtendedEvents.php',

	// Models
	'HeimrichHannot\CalendarEventsModel' => 'system/modules/extended_events/models/CalendarEventsModel.php',

	// Modules
	'HeimrichHannot\ModuleEventChooser'  => 'system/modules/extended_events/modules/ModuleEventChooser.php',

	// Widgets
	'SubEventList'                       => 'system/modules/extended_events/widgets/SubEventList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_event_chooser' => 'system/modules/extended_events/templates',
));
