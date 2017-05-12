# Calendar Plus

[![Latest Stable Version](https://poser.pugx.org/heimrichhannot/contao-calendar_plus/v/stable)](https://packagist.org/packages/heimrichhannot/contao-calendar_plus)
[![Total Downloads](https://poser.pugx.org/heimrichhannot/contao-calendar_plus/downloads)](https://packagist.org/packages/heimrichhannot/contao-calendar_plus)
[![Monthly Downloads](https://poser.pugx.org/heimrichhannot/contao-calendar_plus/d/monthly)](https://packagist.org/packages/heimrichhannot/contao-calendar_plus)
[![License](https://poser.pugx.org/heimrichhannot/contao-calendar_plus/license)](https://packagist.org/packages/heimrichhannot/contao-calendar_plus)

A collection of enhancements for the contao calendar module.

## Features

- subevents (add subevents to an event)
- docents entity (calendar sub-table)
- promoters entity (calendar sub-table)
- eventtypes entity (calendar sub-table)
- additional event fields like locationAdditional,street,postal,city,coordinates,addMap,website,docents,promoters, eventtype and many more
- Module EventListPlus (with additional group by month)
- Module EventReaderPlus (possibility to show details in modal window, with next/prev event navigation and browser history support)
- Module Eventfilter (filter list by date, promoter, docents, eventtype and keyword (make usage of contao-search) many more)
- Module Eventchooser (Dropdown, Event Selector)

### Hooks

Name | Arguments | Expected return value | Description
 ---------- | ---------- | ---------- | ---------
addEventDetailsToTemplate | $objTemplate, $arrEvent, $objModule | void | manipulate the template data to enable modal use 

