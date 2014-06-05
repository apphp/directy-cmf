<?php
/**
 * Bootstrap - bootstrap component class for application
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct
 * setTimeZone
 * setSiteInfo
 * setDefaultLanguage
 * setDefaultCurrency
 *
 * STATIC
 * -------------------------------------------
 * init
 *
 */

class Bootstrap extends CComponent
{

	/**
	 * Class default constructor
	 */
	function __construct()
	{
        A::app()->attachEventHandler('onBeginRequest', array($this, 'setTimeZone'));
		A::app()->attachEventHandler('onBeginRequest', array($this, 'setSiteInfo'));
		A::app()->attachEventHandler('onBeginRequest', array($this, 'setDefaultLanguage'));
		//A::app()->attachEventHandler('onBeginRequest', array($this, 'setDefaultCurrency'));
		
		// un-comment if 'non-batch' cron job type is used
		//Cron::run();
	}

	/**
	 * Sets timezone according to database settings
	 */	
	public function setTimeZone()
	{
		$settings = Settings::model()->findByPk(1);
		if($settings->time_zone != ''){
			$timeZone = $settings->time_zone;
		}else{
			$timeZone = CConfig::get('defaultTimeZone', 'UTC');
		}
		A::app()->getLocalTime()->setTimeZone($timeZone);
	}
    
	/**
	 * Sets site info
	 */	
	public function setSiteInfo()
	{
		SiteSettings::setSiteInfo();
	}
	
	/**
	 * Sets default language
	 */	
	public function setDefaultLanguage()
	{
		if(A::app()->getSession()->get('language') == ''){
			$defaultLang = Languages::model()->getDefaultLanguage();
			if($defaultLang != '') A::app()->setLanguage($defaultLang);
		}
	}

	/**
	 * Sets default currency
	 */	
	public function setDefaultCurrency()
	{
		if(A::app()->getSession()->get('currency_code') == ''){
			$defaultCurrency = Currencies::model()->find('is_default = 1');
			if(!empty($defaultCurrency)){
				$params = array();
				$params['symbol'] = $defaultCurrency[0]['symbol'];
				$params['symbol_place'] = $defaultCurrency[0]['symbol_place'];
				$params['decimals'] = $defaultCurrency[0]['decimals'];
				$params['rate'] = $defaultCurrency[0]['rate'];
				A::app()->setCurrency($defaultCurrency[0]['code'], $params);
			}
		}
	}

	/**
     *	Returns the instance of object
     *	@return Bootstrap class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}


}