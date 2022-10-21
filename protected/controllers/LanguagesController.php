<?php
/**
 * Languages frontend controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct
 * indexAction
 * changeAction				
 *
 */

class LanguagesController extends CController
{    
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Set backend mode
		Website::setFrontend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
	
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect(Website::getDefaultPage());
	}
    
    /**
     * Changes language on site
	 * @param string $lang code of the new site language
     */
    public function changeAction($lang = null)
    {
        // If redirected from dropdown list
        if(empty($lang)) $lang = A::app()->getRequest()->getQuery('lang');
        
        // Check for existing $lang in DB
        if($result = Languages::model()->find("code = :code AND used_on IN ('front-end','global') AND is_active = 1", array(':code'=>$lang))){
            $params = array(
				'name' => $result->name,
				'name_native' => $result->name_native,
                'locale' => $result->lc_time_name,
                'direction' => $result->direction,
				'icon' => $result->icon,
            );
            A::app()->setLanguage($lang, $params);
        }
		
		$referrerPage = Website::getReferrerPage();
		$defaultPage = Website::getDefaultPage();
		$baseUrl = A::app()->getRequest()->getBaseUrl();
		
		// If referrer page exists and it comes from current domain redirect to referrer URL, otherwise to default page		
		if(!empty($referrerPage) && preg_match('/'.preg_quote($baseUrl, '/').'/i', $referrerPage)){
			$this->redirect($referrerPage, true);
		}else{
			$this->redirect($defaultPage);
		}
    }

}