<?php
/**
 * PaymentProviders controller
 *
 * PUBLIC:                  PRIVATE:
 * ---------------          ---------------
 * __construct              _checkActionAccess
 * indexAction
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 *
 */

class PaymentProvidersController extends CController
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
        Website::setMetaTags(array('title'=>A::t('app', 'Payment Providers Management')));
		// Set backend mode
		Website::setBackend();


        $this->_cRequest = A::app()->getRequest();
        $this->_cSession = A::app()->getSession();

        $this->_view->backendPath = $this->_backendPath;
        $this->_view->numberFormat = Bootstrap::init()->getSettings('number_format');
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';

        $this->_view->usedOn = array('front-end'=>A::t('app', 'Front-end'), 'back-end'=>A::t('app', 'Back-end'), 'global'=>A::t('app', 'Global'));
        $this->_view->modes = array('0'=>A::t('app', 'Test Mode'), '1'=>A::t('app', 'Real Mode'));
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect($this->_backendPath.'paymentProviders/manage');
    }

    /**
     * Manage payment providers action handler
     */
    public function manageAction()
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('view', 'payment_providers', 'dashboard/index');

        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->render($this->_backendPath.'paymentProviders/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('edit', 'payment_providers', 'dashboard/index');

        $this->_view->requiredFields = array('merchant_id'=>A::t('app', 'Merchant ID'), 'merchant_code'=>A::t('app', 'Merchant Code'), 'merchant_key'=>A::t('app', 'Merchant Key'));

        $this->_view->render($this->_backendPath.'paymentProviders/add');
    }

    /**
     * Edit payment providers action handler
     * @param int $id
     */
    public function editAction($id = 0)
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('edit', 'payment_providers', 'dashboard/index');

        $paymentProvider = $this->_checkActionAccess($id);
		if(!$paymentProvider){
			$this->redirect($this->_backendPath.'paymentProviders/manage');
		}
        
        $this->_view->paymentProvider = $paymentProvider;
        
        $this->_view->render($this->_backendPath.'paymentProviders/edit');
    }

    /**
     * Change status payment providers action handler
     * @param int $id the payment provider ID
     */
    public function changeStatusAction($id)
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('edit', 'payment_providers', 'dashboard/index');

        $paymentProvider = $this->_checkActionAccess($id);
		if(!$paymentProvider){
			$this->redirect($this->_backendPath.'paymentProviders/manage');
		}
		
		// Check if the payment provider is default
		if($paymentProvider->is_default){
			$alert = A::t('app', 'Change Status Default Alert');
			$alertType = 'error';
		}elseif(PaymentProviders::model()->updateByPk($id, array('is_active'=>($paymentProvider->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
		}else{
			$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
		}
		 
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'paymentProviders/manage');
    }

    /**
     * Delete action handler
     * @param int $id
     */
    public function deleteAction($id)
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('edit', 'payment_providers', 'dashboard/index');

        $paymentProvider = PaymentProviders::model()->findByPk($id);
        if(!$paymentProvider){
            $this->redirect($this->_backendPath.'paymentProviders/manage');
        }

        // Check if the country is default
        if($paymentProvider->is_default){
            $alert = A::t('app', 'Delete Default Alert');
            $alertType = 'error';
        }elseif($paymentProvider->delete()){
            if($paymentProvider->getError()){
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
                $alert = $paymentProvider->getError() ? $paymentProvider->getErrorMessage() : A::t('app', 'Delete Error Message');
                $alertType = 'error';
            }
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect($this->_backendPath.'paymentProviders/manage');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return PaymentProviders
     */
    private function _checkActionAccess($id = 0)
    {
        $paymentProvider = PaymentProviders::model()->findByPk($id);
        if(!$paymentProvider){
            $this->redirect($this->_backendPath.'paymentProviders/manage');
        }
        return $paymentProvider;
    }
}
