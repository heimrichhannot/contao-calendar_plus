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
	'HeimrichHannot\CalendarPlus\CalendarDocentsModel'    => 'system/modules/calendar_plus/models/CalendarDocentsModel.php',
	'HeimrichHannot\CalendarPlus\CalendarEventtypesModel' => 'system/modules/calendar_plus/models/CalendarEventtypesModel.php',
	'HeimrichHannot\CalendarPlus\CalendarPromotersModel'  => 'system/modules/calendar_plus/models/CalendarPromotersModel.php',

	// Modules
	'HeimrichHannot\CalendarPlus\ModuleEventListPlus'     => 'system/modules/calendar_plus/modules/ModuleEventListPlus.php',
	'HeimrichHannot\CalendarPlus\ModuleEventFilter'       => 'system/modules/calendar_plus/modules/ModuleEventFilter.php',
	'HeimrichHannot\CalendarPlus\ModuleEventChooser'      => 'system/modules/calendar_plus/modules/ModuleEventChooser.php',
	'HeimrichHannot\CalendarPlus\ModuleEventReaderPlus'   => 'system/modules/calendar_plus/modules/ModuleEventReaderPlus.php',

	// Widgets
	'HeimrichHannot\CalendarPlus\SubEventList'            => 'system/modules/calendar_plus/widgets/SubEventList.php',

	// Classes
	'HeimrichHannot\CalendarPlus\EventsPlusHelper'        => 'system/modules/calendar_plus/classes/EventsPlusHelper.php',
	'HeimrichHannot\CalendarPlus\EventsPlus'              => 'system/modules/calendar_plus/classes/EventsPlus.php',
	'HeimrichHannot\CalendarPlus\EventFilterHelper'       => 'system/modules/calendar_plus/classes/EventFilterHelper.php',
	'HeimrichHannot\CalendarPlus\ExtendedEvents'          => 'system/modules/calendar_plus/classes/ExtendedEvents.php',
	'HeimrichHannot\CalendarPlus\EventFilterForm'         => 'system/modules/calendar_plus/classes/EventFilterForm.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'event_list_plus'      => 'system/modules/calendar_plus/templates/events',
	'eventmodaldialog'     => 'system/modules/calendar_plus/templates/events',
	'eventmodal_full'      => 'system/modules/calendar_plus/templates/events',
	'mod_eventfilter'      => 'system/modules/calendar_plus/templates/modules',
	'mod_event_modal_ajax' => 'system/modules/calendar_plus/templates/modules',
	'mod_event_modal'      => 'system/modules/calendar_plus/templates/modules',
	'mod_event_chooser'    => 'system/modules/calendar_plus/templates/modules',
	'mod_eventlist_plus'   => 'system/modules/calendar_plus/templates/modules',
	'form_eventfilter'     => 'system/modules/calendar_plus/templates/form',
	'block_modal_event'    => 'system/modules/calendar_plus/templates/block',
));
