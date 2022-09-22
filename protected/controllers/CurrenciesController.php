<?php
/**
 * Currencies controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * changeAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
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

        // Set meta tags according to active currencies
    	Website::setMetaTags(array('title'=>A::t('app', 'Currencies Management')));
        // Set backend mode
        Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->numberFormat = Bootstrap::init()->getSettings('number_format');
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

   	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('currencies/manage');        
	}
    
    /**
     * Changes currencies on site
	 * @param string $currency_code code of the new currency
     */
    public function changeAction($currency_code)
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
        
		$referrerPage = Website::getRefererPage();
		$defaultPage = Website::getDefaultPage();
		$currentPage = Website::getCurrentPage();
		$baseUrl = A::app()->getRequest()->getBaseUrl();
		
		// If referrer page exists and it comes from current domain redirect to referrer URL, otherwise to default page		
		if(!empty($referrerPage) && preg_match('/'.preg_quote($baseUrl, '/').'/', $referrerPage)){
			$this->redirect($referrerPage, true);
		}else{
			$this->redirect($defaultPage);
		}
    }
    
    /**
     * Manage currency action handler
     */
	public function manageAction()
	{
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to manage currencies
        if(!Admins::hasPrivilege('currencies', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
    	$this->_view->render('currencies/manage');        
	}

	/**
	 * Add new currency action handler
	 */
	public function addAction()
	{
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to add currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
        $sortOrder = Currencies::model()->count();
        $this->_view->sortOrder = ($sortOrder < 99) ? $sortOrder + 1 : 99;        		
    	$this->_view->render('currencies/add');
	}
	
	/**
	 * Edit currencies action handler
     * @param int $id the currency ID
	 */
	public function editAction($id = 0)
	{
		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to edit currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect('currencies/manage');
		}
		
		$this->_view->currency = $currency;
		$this->_view->render('currencies/edit');
	}

	/**
	 * Delete currency action handler
	 * @param int $id the currency id 
	 */
	public function deleteAction($id = 0)
	{
		// Block access if admin has no active privilege to delete currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
     	
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect('currencies/manage');
		}
		
    	$alert = '';
    	$alertType = '';
	
		// Check if the currency is default
		if($currency->is_default){
			$alert = A::t('app', 'Delete Default Alert');
			$alertType = 'error';
		}elseif($currency->delete()){				
			if($currency->getError()){
				$alert = A::t('app', 'Delete Warning Message');
				$alertType = 'warning';
			}else{		
				$alert = A::t('app', 'Delete Success Message');
				$alertType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';
		   	}else{
				$alert = $currency->getError() ? $currency->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect('currencies/manage');
	}
	
}
