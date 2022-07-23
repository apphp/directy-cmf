<?php
/**
 * Website - component for working with website
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * setBackend
 * setFrontend
 * setDefaultLanguage
 * setMetaTags
 * setInfo
 * sendEmailByTemplate
 * prepareBackendAction
 * prepareLinkByFormat
 * convertToObject
 * 
 */
class Website extends CComponent
{

	/**
	 * Sets site to backend mode
	 */
    public static function setBackend()
    {
        $currentLanguage = A::app()->getLanguage();
        $loggedLanguage = CAuth::getLoggedLang();
        
        if($currentLanguage != $loggedLanguage){
            if($result = Languages::model()->find("code = :code AND used_on IN ('back-end','global') AND is_active = 1", array(':code'=>$loggedLanguage))){
                $params = array(
                    'locale' => $result->lc_time_name,
                    'direction' => $result->direction
                );
                A::app()->setLanguage($loggedLanguage, $params);
			}
        }
        A::app()->view->setTemplate('backend');
    }

	/**
	 * Sets site to frontend mode
	 * @param string $template
	 */
    public static function setFrontend($template = '')
    {
        if(!$template) $template = Bootstrap::init()->getSettings()->template;               
        A::app()->view->setTemplate($template);
    }
    
	/**
	 * Sets default language
	 */	
	public static function setDefaultLanguage()
	{
        if($defaultLang = Languages::model()->find('is_default = 1')){
            $params = array(
                'locale' => $defaultLang->lc_time_name,
                'direction' => $defaultLang->direction
            );
            A::app()->setLanguage($defaultLang->code, $params);    
        }			
	}

	/**
	 * Sets meta tags according to active language
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
	 * Sets meta tags according to active language
	 */
    public static function setInfo()
    {
		// set meta tags according to active language
		$siteInfo = SiteInfo::model()->find('language_code = :languageCode', array(':languageCode'=>A::app()->getLanguage()));
		if($siteInfo && APPHP_MODE != 'hidden'){
			A::app()->view->setMetaTags('title', $siteInfo->meta_title);
			A::app()->view->setMetaTags('keywords', $siteInfo->meta_keywords);
			A::app()->view->setMetaTags('description', $siteInfo->meta_description);

			A::app()->view->siteTitle = $siteInfo->header;
			A::app()->view->siteSlogan = $siteInfo->slogan;
			A::app()->view->siteFooter = $siteInfo->footer;
		}
    }
    
	/**
	 * Send email using template
	 * @param string $emailTo
	 * @param string $templateCode
	 * @param string $templateLang
	 * @param array $params
	 */
    public static function sendEmailByTemplate($emailTo, $templateCode, $templateLang = '', $params = array())
    {
        $template = EmailTemplates::model()->getTemplate($templateCode, $templateLang);
        $templateContent = isset($template['template_content']) ? $template['template_content'] : '';
        $templateSubject = isset($template['template_subject']) ? $template['template_subject'] : '';
        
        // set base variables if not defined        
        if(!isset($params['{SITE_URL}'])) $params['{SITE_URL}'] = A::app()->getRequest()->getBaseUrl();
        if(!isset($params['{WEB_SITE}'])) $params['{WEB_SITE}'] = CConfig::get('name');
        if(!isset($params['{YEAR}'])) $params['{YEAR}'] = LocalTime::currentDate('Y');

        $arrKeys = array();
        $arrValues = array();        
        foreach($params as $key => $val){
            $arrKeys[] = $key;
            $arrValues[] = $val;
        }
        $templateContent = str_ireplace($arrKeys, $arrValues, $templateContent);
        
        $settings = Bootstrap::init()->getSettings();
        CMailer::config(array(
            'mailer'=>$settings->mailer,
            'smtp_auth'=>$settings->smtp_auth,
            'smtp_secure'=>$settings->smtp_secure,
            'smtp_host'=>$settings->smtp_host,
            'smtp_port'=>$settings->smtp_port,
            'smtp_username'=>$settings->smtp_username,
            'smtp_password'=>CHash::decrypt($settings->smtp_password, CConfig::get('password.hashKey')),
        ));
        
        $result = CMailer::send($emailTo, $templateSubject, $templateContent, array('from'=>$settings->general_email));
        if(CMailer::getError()) CDebug::addMessage('errors', 'sending-restore-email', CMailer::getError());
        return $result;
    }

    /**
     * Prepares backend action operations
     * @param string $mode
     * @param string $privilegeCategory
     * @param string $redirectPath
     */
    public static function prepareBackendAction($action = '', $privilegeCategory = '', $redirectPath = '')
    {
        $baseUrl = A::app()->getRequest()->getBaseUrl();

        // block access to this action for not-logged users
        CAuth::handleLogin('backend/login');

        // block access if admin has no privileges to view modules
        if(!Admins::hasPrivilege('modules', 'view')){
            header('location: '.$baseUrl.'backend/dashboard');
            exit;        
        }

        if(in_array($action, array('add', 'insert', 'edit', 'update', 'delete'))){
            // block access if admin has no privileges to add/edit modules
            if(!Admins::hasPrivilege('modules', 'edit')){
                header('location: '.$baseUrl.'backend/dashboard');
                exit;        
            }
        }
        if(in_array($action, array('add', 'insert'))){
            // block access if admin has no privileges to add records
            if(!Admins::hasPrivilege($privilegeCategory, 'add')){
                header('location: '.$baseUrl.$redirectPath);
                exit;        
            }
        }
        if(in_array($action, array('edit', 'update'))){
            // block access if admin has no privileges to edit records
            if(!Admins::hasPrivilege($privilegeCategory, 'edit')){
                header('location: '.$baseUrl.$redirectPath);
                exit;        
            }
        }
        if(in_array($action, array('delete'))){
            // block access if admin has no privileges to delete records
            if(!Admins::hasPrivilege($privilegeCategory, 'delete')){
                header('location: '.$baseUrl.$redirectPath);
                exit;        
            }
        }
        
        // set backend mode
        Website::setBackend();        
    }
	
    /**
     * Prepares backend action operations
     * @param string $module
     * @param string $propertyKey
     * @param int $id
     * @param string $name
     */
    public static function prepareLinkByFormat($module = '', $propertyKey = '', $id = '', $name = '')
    {
        $formats = explode(',', ModulesSettings::model()->param($module, $propertyKey, 'source'));
        $linkFormat = ModulesSettings::model()->param($module, $propertyKey);
        $link = '';
        foreach($formats as $key => $val){
            if($val == $linkFormat){
                $link = str_replace(array('/ID', '/Name'), array('/'.$id, '/'.CString::seoString($name)), $linkFormat);
                break;
            }
        }
        return $link;
    }
   
}