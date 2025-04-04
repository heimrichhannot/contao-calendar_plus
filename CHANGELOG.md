# Changelog
All notable changes to this project will be documented in this file.

## [2.8.0] - 2025-04-04
- Changed: many additional variables are lazy loaded now
- Changed: require at least contao 4.13.46
- Changed: some adjustments to child events calculation to improve performance
- Fixed: page not found exceptions not correctly handled

## [2.7.0] - 2025-02-19
- Changed: better indication of parent event
- Changed: added urls to parent events
- Fixed: default order directive in model using non existing field

## [2.6.1] - 2025-02-18
- Fixed: missing token on subevents link in backend
- Fixed: performance issue on memberDocents field

## [2.6.0] - 2025-02-18
- Changed: make if visible if in subevent list
- Changed: adjust subevent button icon to indicate if event has subevents
- Changed: require at least php 7.4
- Fixed: missing showSubEvents action label

## [2.5.3] - 2024-12-11
- Fixed: php8 warning

## [2.5.2] - 2024-05-15
- Fixed: user permission on subevents 

## [2.5.1] - 2023-02-28
- Fixed: variable not deserialized in some cases

## [2.5.0] - 2022-04-05
- Changed: reduced number of database call in list module (Attention! event details and hasDetails attribute are now callback functions as in contao core)
- Fixed: some deprecations and imports

## [2.4.0] - 2022-03-24
- Added: php 8 support
- Added: reduce number of database/model calls for recurring events
- Changed: some adjustments to contao 4
- Removed: php <7.1 support
- Removed: contao <4.4 support

## [2.3.6] - 2022-01-18
- Fixed: tl_calendar_events_plus::getParentEventChoices() exception if dc is null

## [2.3.5] - 2021-12-13
- Fixed: tl_calendar_events_plus::getRooms() parameter type
- Fixed: warning in tl_calendar_events_plus::getRooms()

## [2.3.4] - 2021-12-09
- Fixed: type errors on option callbacks in tl_calendar_events_plus if datacontainer is null

## [2.3.3] - 2021-08-03
- throw a 404 if the event couldn't be found

## [2.3.2] - 2020-05-14
- made `tl_calendar_eventtypes` filter an OR filter according to customer wish

## [2.3.1] - 2020-05-14
- `tl_calendar_eventtypes` checkPermission handling (`edit`)

## [2.3.0] - 2020-05-08
- removed `GROUP BY` options from `CalendarPlusEventsModel`

## [2.2.2] - 2020-02-04

- restricted `listTime` event sorting when `cal_alwaysShowParents` enabled

## [2.2.1] - 2020-01-24

- fixed model issues

## [2.2.0] - 2019-03-14

### Added
- `heimrichhannot/contao-components` support, in order disable assets on page layout

## [2.1.8] - 2019-02-21

### Fixed
- possible memory error when calculating parent events in backend

## [2.1.7] - 2019-01-23

### Fixed
- `ModuleEventListPlus` groups now multi day events only of `cal_noSpan` is enabled and timediff in days is greater than a

## [2.1.6] - 2019-01-09

### Fixed
- `ModuleEventReaderPlus` -> array cast issue

## [2.1.5] - 2018-08-21

### Fixed
- `ModuleEventReaderPlus` now properly renders event when RebuildIndex is running (refactored modal check)

## [2.1.4] - 2018-08-21

### Fixed
- `ModuleEventListPlus` groups now multi day events only of `cal_noSpan` is enabled

## [2.1.3] - 2018-08-21

### Fixed
- `tl_calendar_eventtypes` checkPermission handling (`edit`)

## [2.1.2] - 2018-08-21

### Fixed
- `tl_calendar_eventtypes` checkPermission handling

## [2.1.1] - 2018-08-21

### Fixed
- contao 4 and event registration issues

## [2.1.0] - 2018-03-05

### Changed
- removed jscroll from repository, added as composer dependency

## [2.0.4] - 2018-02-27

### Fixed

- contao search implementation for `q` filter-field, wrong form action

## [2.0.3] - 2018-02-20

### Fixed

- parentEvent id must not equal event id
- back button in epid list view fixed
- improved event filter for childEvents

## [2.0.2] - 2018-02-20

### Fixed

- duplicated parentEvents when `cal_alwaysShowParents` enabled
- wrong `listTime` event sorting when `cal_alwaysShowParents` enabled

## [2.0.1] - 2018-02-08

### Added

- `tl_module.cal_alwaysShowParents` to always show parent events, also if child event was found by filter criteria

### Fixed
- `CalendarPlusEventsModel::getUniqueCityNamesByPids()` current handling
- `CalendarPlusEventsModel::getUniquePromotersByPids()` current handling
- `CalendarPlusEventsModel::getUniqueDocentsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueMemberDocentsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueHostsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueMemberHostsByPids()` current handling
- `CalendarPlusEventsModel::getUniquePromoterNamesByPids()` current handling

## [2.0.0] - 2018-02-06


## [1.4.8] - 2018-02-27

### Fixed

- contao search implementation for `q` filter-field, wrong form action

## [1.4.7] - 2018-02-20

### Fixed

- parentEvent id must not equal event id
- back button in epid list view fixed
- improved event filter for childEvents

### Changed
- `heimrichhannot/contao-formhybrid` 3.x dependency

## [1.4.6] - 2018-02-20

### Fixed

- duplicated parentEvents when `cal_alwaysShowParents` enabled
- wrong `listTime` event sorting when `cal_alwaysShowParents` enabled

## [1.4.5] - 2018-02-01

### Added

- `tl_module.cal_alwaysShowParents` to always show parent events, also if child event was found by filter criteria

### Fixed
- `CalendarPlusEventsModel::getUniqueCityNamesByPids()` current handling
- `CalendarPlusEventsModel::getUniquePromotersByPids()` current handling
- `CalendarPlusEventsModel::getUniqueDocentsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueMemberDocentsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueHostsByPids()` current handling
- `CalendarPlusEventsModel::getUniqueMemberHostsByPids()` current handling
- `CalendarPlusEventsModel::getUniquePromoterNamesByPids()` current handling

## [1.4.4] - 2018-02-01

### Fixed
- changed $arrItems to $arrArchives in getEventTypesFieldsByArchive

## [1.4.3] - 2018-01-24

### Added
- `formHybridAction` to `eventfilter` module type

## [1.4.2] - 2018-01-24

### Added
- some english translations

### Changed
- licence LGPL-3.0+ is now LGPL-3.0-or-later

## [1.4.1] - 2018-01-12
             
#### Added
- added linkedMember to tl_calendar_events. link a member to a events

## [1.4.0] - 2018-01-10

### Changed
- removed contao-calendar_plus as dependency (added to suggest)

## [1.3.6] - 2018-01-05

### Fixed
- added contao version compare. if contao >= 4 no strUrl needed for addEvent function

## [1.3.5] - 2017-11-06

### Fixed
- if `col_noSpan` is disabled, events are not grouped while having multiple days -> fixed pagination in this mode (ignore total from database, use total event count from `Eventsplus::getAllEvents`)
- fixed `q` filter search if contao search returned no result (no index available for current event) 

## [1.3.4] - 2017-07-21

### Fixed
- tl_module_formhybrid callback

## [1.3.3] - 2017-06-21

### Fixed
- "parentEvent" field sql default value

## [1.3.2] - 2017-06-13

### Fixed
- dca field parentEvent's css classes

## [1.3.1] - 2017-06-12

### Fixed
- "event" field sql default value

## [1.3.0] - 2017-06-07

### Changed
- improved calendar list getAllEvents and pagination handling, now events will be limited within sql query, not within array list

## [1.2.33] - 2017-05-12

### Added
- fixed render reader within list, when modal is present

## [1.2.32] - 2017-05-12

### Added
- modal support

## [1.2.31] - 2017-05-08

### Added
- promoter logo & calendar archive upload folder field

## [1.2.30] - 2017-04-12

### Fixed
- hide unpublished event types within eventfilter

## [1.2.29] - 2017-04-12
- created new tag for composer dependency

## [1.2.28] - 2017-04-05

### Changed
- changed String to StringUtil and this->$callback to this->{callback}

## [1.2.27] - 2017-04-05

### Changed
- added php7 support. fixed contao-core dependency

## [1.2.26] - 2017-04-05

### Added
- do not render modal when share-print template is set within module config

## [1.2.25] - 2017-02-17

### Added
- roomList now available within reader and list template

## [1.2.24] - 2017-01-30

### Fixed
- tl_calendar_room permission issues

## [1.2.23] - 2017-01-16

### Added
- startDateFormatted and endDateFormatted in reader module

## [1.2.22] - 2017-01-12

### Fixed
- event registration connection

## [1.2.21] - 2017-01-05

### Fixed
- CalendarPlusEventsModel::getUniqueCityNamesByPids() now correctly recognize $currentOnly period
- CalendarPlusEventsModel::getUniquePromotersByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueDocentsByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueMemberDocentsByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueHostsByPids() now correctly recognize $currentOnly period
- CalendarPlusEventsModel::getUniqueMemberHostsByPids() now correctly recognize $currentOnly period
- CalendarPlusEventsModel::getUniquePromoterNamesByPids() now correctly recognize $currentOnly period

## [1.2.20] - 2017-01-05

### Fixed
- Changelog dates for 1.2.18 & 1.2.19 to 2017

## [1.2.19] - 2017-01-05

### Added
- Poser badges added to README.md

## [1.2.18] - 2017-01-05

### Fixed
- CalendarPlusEventsModel::getUniqueCityNamesByPids() now correctly recognize $currentOnly period
- CalendarPlusEventsModel::getUniquePromotersByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueDocentsByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueMemberDocentsByPids() now correctly recognize $currentOnly period 
- CalendarPlusEventsModel::getUniqueHostsByPids() now correctly recognize $currentOnly period  
- CalendarPlusEventsModel::getUniqueMemberHostsByPids() now correctly recognize $currentOnly period  
- CalendarPlusEventsModel::getUniquePromoterNamesByPids() now correctly recognize $currentOnly period  


## [1.2.17] - 2016-12-12

### Fixed
- disable cache for \Controller::replaceInsertTags() within ajax request, fixed boolean to false (1.2.16 had true in there)

## [1.2.16] - 2016-12-12

### Fixed
- disable cache for \Controller::replaceInsertTags() within ajax request

## [1.2.15] - 2016-12-06

### Fixed
- filter options filecache, clear cache did trigger old phpfastcache 4.0 clean function

## [1.2.14] - 2016-12-05

### Fixed
- history url/title reworked
- filter fixed
