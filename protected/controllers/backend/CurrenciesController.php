<?php
/**
 * Currencies controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * updateRatesAction
 *
 */

class CurrenciesController extends CController
{
	private $_backendPath = '';

	/**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

        // Set meta tags according to active currencies
    	Website::setMetaTags(array('title'=>A::t('app', 'Currencies Management')));
        // Set backend mode
        Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

	    // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
        $this->_view->numberFormat = Bootstrap::init()->getSettings('number_format');

		$this->_view->backendPath = $this->_backendPath;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

   	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect($this->_backendPath.'currencies/manage');
	}

    /**
     * Manage currency action handler
     */
	public function manageAction()
	{
		// Block access if admin has no active privilege to manage currencies
        if(!Admins::hasPrivilege('currencies', array('view', 'edit'))){
        	$this->redirect($this->_backendPath.'dashboard/index');
        }
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
    	$this->_view->render($this->_backendPath.'currencies/manage');
	}

	/**
	 * Add new currency action handler
	 */
	public function addAction()
	{
		// Block access if admin has no active privilege to add currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
        $sortOrder = Currencies::model()->count();
        $this->_view->sortOrder = ($sortOrder < 99) ? $sortOrder + 1 : 99;        		
    	$this->_view->render($this->_backendPath.'currencies/add');
	}
	
	/**
	 * Edit currencies action handler
     * @param int $id the currency ID
	 */
	public function editAction($id = 0)
	{
		// Block access if admin has no active privilege to edit currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect($this->_backendPath.'currencies/manage');
		}
		
		$this->_view->currency = $currency;
		$this->_view->render($this->_backendPath.'currencies/edit');
	}

    /**
     * Change status currencies action handler
     * @param int $id the currency ID
     */
    public function changeStatusAction($id)
    {
		// Block access if admin has no active privilege to edit currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
		$currency = Currencies::model()->findByPk($id);
		if(!$currency){
			$this->redirect($this->_backendPath.'currencies/manage');
		}
		
		// Check if the currency is default
		if($currency->is_default){
			$alert = A::t('app', 'Change Status Default Alert');
			$alertType = 'error';
		}elseif(Currencies::model()->updateByPk($id, array('is_active'=>($currency->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
		}else{
			$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
		}
		 
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'currencies/manage');
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
			$this->redirect($this->_backendPath.'currencies/manage');
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

		$this->redirect($this->_backendPath.'currencies/manage');
	}
	
	/**
	 * Update currency rates
	 * @return void
	 */
	public function updateRatesAction()
	{
		// Block access if admin has no active privilege to delete currencies
        Website::prepareBackendAction('edit', 'currencies', 'currencies/manage');
		
		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			// Update currency rates
			$result = Currencies::updateRates();
			$alert = $result['alert'];
			$alertType = $result['alertType'];
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'currencies/manage');
	}	
	
}
