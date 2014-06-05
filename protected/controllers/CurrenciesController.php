<?php
/**
* CurrenciesController
*
* PUBLIC:                  PRIVATE
* -----------              ------------------
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

        // set meta tags according to active currencies
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Currencies Management')));
		
    	A::app()->view->setTemplate('backend');        
        $this->view->actionMessage = '';
        $this->view->errorField = '';
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
        // if redirected from dropdown list
        if(empty($currency_code)) $currency_code = A::app()->getRequest()->getQuery('currency');
        
        // check for existing currency in DB
        if($result = Currencies::model()->find('code = :code AND is_active = 1', array(':code'=>$currency_code))){
			$params = array();
			$params['symbol'] = $result[0]['symbol'];
			$params['symbol_place'] = $result[0]['symbol_place'];
			$params['decimals'] = $result[0]['decimals'];
			$params['rate'] = $result[0]['rate'];
			A::app()->setCurrency($currency_code, $params);
        }
        $this->redirect('index/index');
    }
    
    /**
     * Manage currency action handler
     * @param string $msg 
     */
	public function manageAction($msg = '')
	{
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view currencies
        if(!Admins::hasPrivilege('currencies', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
		switch($msg){
			case 'added':
				$message = A::t('core', 'The adding operation has been successfully completed!');
				break;
			case 'updated':
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;
			default:
				$message = '';
		}
		if(!empty($message)){
			$this->view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
		}
		
    	$this->view->render('currencies/manage');        
	}

	/**
	 * Add new currency action handler
	 */
	public function addAction()
	{
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view currencies
        if(!Admins::hasPrivilege('currencies', 'edit')){
        	$this->redirect('backend/index');
        }
		
    	$this->view->render('currencies/add');
	}
	
	/**
	 * Edit currencies action handler
     * @param int $id the currency ID
	 */
	public function editAction($id = 0)
	{
		// block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view currencies
        if(!Admins::hasPrivilege('currencies', 'edit')){
        	$this->redirect('backend/index');
        }
		
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect('currencies/manage');
		}
		
		$this->view->currency = $currency;
		$this->view->render('currencies/edit');
	}

	/**
	 * Delete currency action handler
	 * @param int $id the currency id 
	 */
	public function deleteAction($id = 0)
	{
		// block access if admin has no active privilege to view currencies
        if(!Admins::hasPrivilege('currencies', 'edit')){
        	$this->redirect('backend/index');
        }
     	
		$msg = '';
		$errorType = '';
	
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect('currencies/manage');
		}
		
		// check if the currency is default
		if($currency->is_default){
			$msg = A::t('app', 'Delete Default Alert');
			$errorType = 'error';
		}else if($currency->delete()){				
			if($currency->getError()){
				$msg = A::t('app', 'Delete Warning Message');
				$errorType = 'warning';
			}else{		
				$msg = A::t('app', 'Delete Success Message');
				$errorType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$errorType = 'warning';
		   	}else{
				$msg = A::t('app', 'Delete Error Message');
				$errorType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
		}
		$this->view->render('currencies/manage');
	}
	
	
}