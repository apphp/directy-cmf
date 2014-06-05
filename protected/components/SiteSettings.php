<?php
/**
 * SiteSettings
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * setMetaTags
 * setSiteInfo
 * convertToObject
 * 
 */
class SiteSettings extends CComponent
{

	/**
	 * Set meta tags according to active language
	 * @param array $string
	 */
    public static function setMetaTags($params = array())
    {
    	if(isset($params['title'])){
			$viewTitle = A::app()->view->siteTitle;
			A::app()->view->setMetaTags('title', $params['title'].($viewTitle != '' ? ' | '.$viewTitle : ''));
		}
    	if(isset($params['keywords'])) A::app()->view->setMetaTags('keywords', $params['keywords']);
    	if(isset($params['description'])) A::app()->view->setMetaTags('description', $params['description']);
    }

	/**
	 * Set meta tags according to active language
	 */
    public static function setSiteInfo()
    {
		// set meta tags according to active language
		$siteInfo = SiteInfo::model()->find('language_code = :languageCode', array(':languageCode'=>A::app()->getLanguage()));
		
		if(APPHP_MODE != 'hidden'){
			A::app()->view->setMetaTags('title', $siteInfo[0]['meta_title']);
			A::app()->view->setMetaTags('keywords', $siteInfo[0]['meta_keywords']);
			A::app()->view->setMetaTags('description', $siteInfo[0]['meta_description']);

			A::app()->view->siteTitle = $siteInfo[0]['header'];
			A::app()->view->siteSlogan = $siteInfo[0]['slogan'];
			A::app()->view->siteFooter = $siteInfo[0]['footer'];
		}
    }
	
    /**
     * Converts array with results of select(like find) to CActiveRecord object (like we get from findByPk)
     */
    public static function convertToObject($activeRecordObj, $selectResults)
    {
    	if(isset($activeRecordObj) && isset($selectResults) && is_array($selectResults)){
    		foreach($selectResults as $key => $val){
    			$activeRecordObj->$key = $val;
    		}
    		return $activeRecordObj;
    	}else{
    		return null;
    	}
    }
    
}