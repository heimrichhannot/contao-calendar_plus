<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	'HeimrichHannot\CalendarPlus\CalendarPlusEventsModel'        => 'system/modules/calendar_plus/models/CalendarPlusEventsModel.php',
	'HeimrichHannot\CalendarPlus\CalendarEventtypesArchiveModel' => 'system/modules/calendar_plus/models/CalendarEventtypesArchiveModel.php',
	'HeimrichHannot\CalendarPlus\CalendarDocentsModel'           => 'system/modules/calendar_plus/models/CalendarDocentsModel.php',
	'HeimrichHannot\CalendarPlus\CalendarPlusModel'              => 'system/modules/calendar_plus/models/CalendarPlusModel.php',
	'HeimrichHannot\CalendarPlus\CalendarEventtypesModel'        => 'system/modules/calendar_plus/models/CalendarEventtypesModel.php',
	'HeimrichHannot\CalendarPlus\CalendarPromotersModel'         => 'system/modules/calendar_plus/models/CalendarPromotersModel.php',

	// Modules
	'HeimrichHannot\CalendarPlus\ModuleEventListPlus'            => 'system/modules/calendar_plus/modules/ModuleEventListPlus.php',
	'HeimrichHannot\CalendarPlus\ModuleEventFilter'              => 'system/modules/calendar_plus/modules/ModuleEventFilter.php',
	'HeimrichHannot\CalendarPlus\ModuleEventChooser'             => 'system/modules/calendar_plus/modules/ModuleEventChooser.php',
	'HeimrichHannot\CalendarPlus\ModuleEventReaderPlus'          => 'system/modules/calendar_plus/modules/ModuleEventReaderPlus.php',

	// Widgets
	'HeimrichHannot\CalendarPlus\SubEventList'                   => 'system/modules/calendar_plus/widgets/SubEventList.php',

	// Classes
	'HeimrichHannot\CalendarPlus\EventsPlusHelper'               => 'system/modules/calendar_plus/classes/EventsPlusHelper.php',
	'HeimrichHannot\CalendarPlus\EventsPlus'                     => 'system/modules/calendar_plus/classes/EventsPlus.php',
	'HeimrichHannot\CalendarPlus\EventFilterHelper'              => 'system/modules/calendar_plus/classes/EventFilterHelper.php',
	'HeimrichHannot\CalendarPlus\ExtendedEvents'                 => 'system/modules/calendar_plus/classes/ExtendedEvents.php',
	'HeimrichHannot\CalendarPlus\EventFilterForm'                => 'system/modules/calendar_plus/classes/EventFilterForm.php',
	'HeimrichHannot\CalendarPlus\EventModelHelper'               => 'system/modules/calendar_plus/classes/EventModelHelper.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'event_list_plus'                 => 'system/modules/calendar_plus/templates/events',
	'eventmodal_full'                 => 'system/modules/calendar_plus/templates/events',
	'event_subevent'                  => 'system/modules/calendar_plus/templates/events',
	'eventnavigation_arrows'          => 'system/modules/calendar_plus/templates/navigation',
	'mod_eventfilter'                 => 'system/modules/calendar_plus/templates/modules',
	'mod_event_modal_ajax'            => 'system/modules/calendar_plus/templates/modules',
	'mod_event_modal'                 => 'system/modules/calendar_plus/templates/modules',
	'mod_event_chooser'               => 'system/modules/calendar_plus/templates/modules',
	'mod_eventlist_plus'              => 'system/modules/calendar_plus/templates/modules',
	'eventfilter_eventtypes_archives' => 'system/modules/calendar_plus/templates/form',
	'formhybrid_eventfilter'          => 'system/modules/calendar_plus/templates/form',
	'block_modal_event'               => 'system/modules/calendar_plus/templates/block',
));
