<?php
/**
 * Currencies frontend controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * changeAction
 *
 */

class CurrenciesController extends CController
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
     * Changes currencies on site
	 * @param string $currency_code code of the new currency
     */
    public function changeAction($currency_code = null)
    {
        // If redirected from dropdown list
        if(empty($currency_code)) $currency_code = A::app()->getRequest()->getQuery('currency');
        
        // Check for existing currency in DB
        if($result = Currencies::model()->find('code = :code AND is_active = 1', array(':code'=>$currency_code))){
			$params = array();
			$params['name'] = $result->name;
			$params['symbol'] = $result->symbol;
			$params['symbol_place'] = $result->symbol_place;
			$params['decimals'] = $result->decimals;
			$params['rate'] = $result->rate;
			A::app()->setCurrency($currency_code, $params);
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