<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\CalendarPlus\Controller;

class PromoterController extends \Frontend
{
	/**
	 * Model
	 * @var Model
	 */
	protected $objModel;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Contact Fields
	 * @var array
	 */
	protected static $arrContact = array('company', 'contactName', 'street', 'postal', 'city', 'country', 'fax', 'phone', 'email', 'website', 'websiteUrl');

	public function __construct($objModel)
	{
		if ($objModel instanceof \Model)
		{
			$this->objModel = $objModel;
		}
		elseif ($objModel instanceof \Model\Collection)
		{
			$this->objModel = $objModel->current();
		}

		parent::__construct();

		$this->arrData = $objModel->row();
	}

	public function parsePromoter($objPromoter, $index = null, array $arrPromoters = array())
	{
		$strTemplate = $this->cal_promoterTemplate ? $this->cal_promoterTemplate : 'cal_promoter_default';

		$objT = new \FrontendTemplate($strTemplate);
		$objT->setData($objPromoter->row());

		if($objPromoter->room && ($objRoom = $objPromoter->getRelated('room')) !== null)
		{
			$objT->room = $objRoom;
		}

		$contact = new \stdClass();
		$hasContact = false;


		if($objPromoter->website)
		{
			$objT->websiteUrl = \HeimrichHannot\Haste\Util\Url::addScheme($objPromoter->website);
		}

		foreach(static::$arrContact as $strField)
		{
			if(!$objT->{$strField})
			{
				continue;
			}

			$hasContact = true;
			$contact->{$strField} = $objT->{$strField};
		}

		$objT->hasContact = $hasContact;
		$objT->contact = $contact;
		$objT->contactTitle = $GLOBALS['TL_LANG']['cal_promoterlist']['contactTitle'];
		$objT->phoneTitle = $GLOBALS['TL_LANG']['cal_promoterlist']['phoneTitle'];
		$objT->faxTitle = $GLOBALS['TL_LANG']['cal_promoterlist']['faxTitle'];
		$objT->emailTitle = $GLOBALS['TL_LANG']['cal_promoterlist']['emailTitle'];
		$objT->websiteTitle = $GLOBALS['TL_LANG']['cal_promoterlist']['websiteTitle'];

		if(!empty($arrPromoters) && $index !== null)
		{
			$objT->cssClass = \HeimrichHannot\Haste\Util\Arrays::getListPositonCssClass($index, $arrPromoters);
		}
		
		return $objT->parse();
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Return the model
	 * @return \Model
	 */
	public function getModel()
	{
		return $this->objModel;
	}
}