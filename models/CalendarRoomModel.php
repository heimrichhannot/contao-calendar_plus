<?php

namespace HeimrichHannot\CalendarPlus;

/**
 * Reads and writes calendar rooms
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $headline
 * @property string  $alias
 * @property integer $author
 * @property integer $date
 * @property integer $time
 * @property string  $subheadline
 * @property string  $teaser
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property boolean $addEnclosure
 * @property string  $enclosure
 * @property string  $source
 * @property integer $jumpTo
 * @property integer $articleId
 * @property string  $url
 * @property boolean $target
 * @property string  $cssClass
 * @property boolean $noComments
 * @property boolean $featured
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 * @property string  $authorName
 *
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findById($id, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByPk($id, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByIdOrAlias($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneBy($col, $val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByPid($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByTstamp($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByTitle($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByAlias($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByName($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByStreet($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByPostal($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByCity($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByCountry($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneBySingleCoords($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByContactName($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByPhone($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByFax($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByEmail($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByWebsite($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByRoom($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByPublished($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByStart($val, $opt=array())
 * @method static \HeimrichHannot\CalendarPlus\CalendarRoomModel|null findOneByStop($val, $opt=array())
 *
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByName($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByStreet($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByPostal($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByCity($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByCountry($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findBySingleCoors($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByContactName($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByPhone($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByFax($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByEmail($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByWebsite($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByRoom($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\HeimrichHannot\CalendarPlus\CalendarRoomModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByName($val, $opt=array())
 * @method static integer countByStreet($val, $opt=array())
 * @method static integer countByPostal($val, $opt=array())
 * @method static integer countByCity($val, $opt=array())
 * @method static integer countByCountry($val, $opt=array())
 * @method static integer countBySingleCoords($val, $opt=array())
 * @method static integer countByContactName($val, $opt=array())
 * @method static integer countByPhone($val, $opt=array())
 * @method static integer countByFax($val, $opt=array())
 * @method static integer countByEmail($val, $opt=array())
 * @method static integer countByWebsite($val, $opt=array())
 * @method static integer countByRoom($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package calendar_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

class CalendarRoomModel extends \Model
{

	protected static $strTable = 'tl_calendar_room';


	public static function findAllByCalendar($intId, array $arrOptions = array())
	{
		$t = static::$strTable;
		$objRoomArchives = CalendarRoomArchiveModel::findByPid($intId);

		if($objRoomArchives === null)
		{
			return null;
		}

		if(!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.title";
		}

		return static::findMultipleByPids($objRoomArchives->fetchEach('id'), $arrOptions);
	}

	public static function findMultipleByPids(array $arrPids, array $arrOptions = array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		return static::findBy($arrColumns, null, $arrOptions);
	}

	public static function findPublishedByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")");

		$time = time();
		$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";

		return static::findBy($arrColumns, null, $arrOptions);
	}

}
