<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus\Utils;


class Options
{
    public static function getPromoterTypes()
    {
        $arrOptions = [];

        $arrConstants = preg_filter('/^CALENDARPLUS_PROMOTER_TYPE_(.*)/', 'CALENDARPLUS_PROMOTER_TYPE_$1', array_keys(get_defined_constants()));

        if (!is_array($arrConstants))
        {
            return $arrOptions;
        }

        foreach ($arrConstants as $strConstant)
        {
            $type              = constant($strConstant);
            $arrOptions[$type] = $type;
        }

        return $arrOptions;
    }
}