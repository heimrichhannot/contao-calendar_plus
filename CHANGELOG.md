# Change Log
All notable changes to this project will be documented in this file.

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
