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
 * deleteAction
 * testCheckoutAction
 * testPaymentAction
 * testPaymentCompleteAction
 * handlePaymentAction
 * successPaymentAction
 *
 */

class PaymentProvidersController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Set backend mode
        Website::setBackend();

        // Set meta tags according to active currencies
        Website::setMetaTags(array('title'=>A::t('app', 'Payment Providers Management')));

        $this->_cRequest = A::app()->getRequest();
        $this->_cSession = A::app()->getSession();

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
        $this->redirect('paymentProviders/manage');
    }

    /**
     * Manage payment providers action handler
     */
    public function manageAction()
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('view', 'payment_providers', 'backend/index');

        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->render('paymentProviders/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        // Block access to this controller to non-logged users
        CAuth::handleLogin(Website::getDefaultPage());

        // Block access if admin has no active privilege to manage payment providers
        Website::prepareBackendAction('edit', 'payment_providers', 'backend/index');

        $this->_view->requiredFields = array('merchant_id'=>A::t('app', 'Merchant ID'), 'merchant_code'=>A::t('app', 'Merchant Code'), 'merchant_key'=>A::t('app', 'Merchant Key'));

        $this->_view->render('paymentProviders/add');
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
        Website::prepareBackendAction('edit', 'payment_providers', 'backend/index');

        $paymentProvider = $this->_checkActionAccess($id);
        $this->_view->paymentProvider = $paymentProvider;

        $this->_view->render('paymentProviders/edit');
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
        Website::prepareBackendAction('edit', 'payment_providers', 'backend/index');

        $paymentProvider = PaymentProviders::model()->findByPk($id);
        if(!$paymentProvider){
            $this->redirect('paymentProviders/manage');
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

        $this->redirect('paymentProviders/manage');
    }

    /**
     * Test checkout action handler
     * Ex.: paymentProviders/testCheckout
     *
     * @param string $type
     */
    public function testCheckoutAction()
    {
        Website::setFrontend();

        if(APPHP_MODE != 'debug'){
            $this->redirect(Website::getDefaultPage());
        }

        $this->_view->providers = PaymentProviders::model()->findAll('is_active = 1');

        $this->_view->render('paymentProviders/checkout');
    }

    /**
     * Test payment action handler
     * Ex.: paymentProviders/testPayment?type=online_order
     *      paymentProviders/testPayment?type=online_credit_card
     *      paymentProviders/testPayment?type=wire_transfer
     *      paymentProviders/testPayment?type=paypal
     *
     */
    public function testPaymentAction()
    {
        Website::setFrontend();

        if(APPHP_MODE != 'debug'){
            $this->redirect(Website::getDefaultPage());
        }

        CLoader::library('ipgw/PaymentProvider.php');

        $type = $this->_cRequest->get('type');

        // PayPal | Online | OnlineCheckOut | Wire
        $type = (!empty($type) && in_array($type, array('online_order', 'online_credit_card', 'wire_transfer', 'paypal'))) ? $type : 'paypal';

        switch($type){
            case 'online_order':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'online_order'");
                break;

            case 'online_credit_card':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'online_credit_card'");
                break;

            case 'wire_transfer':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'wire_transfer'");
                break;

            default:
            case 'paypal':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'paypal'");
                break;
        }

        $this->_view->provider = PaymentProvider::init($type);
        $this->_view->back = 'paymentProviders/testCheckout';
        $this->_view->type = $type;

        $this->_view->render('paymentProviders/payment');
    }

    /**
     * Test payment complete action handler
     * Ex.: paymentProviders/testPaymentComplete
     *
     * @param string $type
     */
    public function testPaymentCompleteAction()
    {
        Website::setFrontend();

        if(APPHP_MODE != 'debug'){
            $this->redirect(Website::getDefaultPage());
        }

        $this->_view->render('paymentProviders/paymentComplete');
    }

    /**
     * Payments handler
     * Ex.: paymentProviders/handlePayment/provider/paypal/handler/modelName/module/moduleName
     *      paymentProviders/handlePayment/provider/paypal/handler/modelName
     *      paymentProviders/handlePayment/paypal/modelName
     *
     * @param string $provider
     */
    public function handlePaymentAction($provider = '', $model = '', $module = '')
    {
        // Set frontend mode
        Website::setFrontend();

        $provider = empty($provider) ? $this->_cRequest->getQuery('provider') : $provider;
        $model    = empty($model)    ? $this->_cRequest->getQuery('handler')  : $model;
        $module   = empty($module)   ? $this->_cRequest->getQuery('module')   : $module;

        $log = APPHP_MODE == 'debug' ? true : false;
        if(!empty($module)){
            $model = !empty($model) ? 'Modules\\'.ucfirst($module).'\Models\\'.ucfirst($model) : '';
            if(@call_user_func_array($model.'::model', array()) === false){
                $model = !empty($model) ? ucfirst($model) : '';
            }
        }else{
            $model = !empty($model) ? ucfirst($model) : '';
        }
        $alert = '';
        $alertType = '';

        $paymentProvider = PaymentProviders::model()->find('code = :code AND is_active = 1', array(':code'=>$provider));
        if($paymentProvider && !empty($model)){

            // Load payment library
            CLoader::library('ipgw/PaymentProvider.php');

            $paymentHandler = PaymentProvider::init($provider);
            $result = $paymentHandler->handlePayment(array('log' => $log));

            $alert = $result['message'];
            $orderInfo = array('payment_provider'=>$provider);

            if($result['error'] != 0){
                // Add here CMF logger
                $alertType = 'error';
                // Status Rejected
                $status = 'rejected';
                $orderInfo = $result['order'];
            }else{
                $alertType = 'error';
                $status = empty($result['order']) ? 'pending' : 'completed';
                $orderInfo = $result['order'];
            }

            if($handlerClass = @call_user_func_array($model.'::model', array())){
                // Call to model for saving data in DB
                if($handlerResult = @call_user_func_array(array($handlerClass, 'paymentHandler'), array($status, $orderInfo))){
                    // here result
                    $alertType = 'success';
                    $alert = A::t('app', 'Thank You! Your order has been successfully completed.');
                }else{
                    $alertType = 'error';
                    $alert = @call_user_func_array(array($handlerClass, 'getErrorMessage'), array());
                }
            }
        }else{
            // Add here CMF logger
            $alertType = 'error';
            $alert = 'Model Not Found or/and Provider not Found';
        }

        $paymentCompletePage = CConfig::get('paymentCompletePage');
        if(!empty($paymentCompletePage)){
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);
            $this->redirect($paymentCompletePage.'/provider/'.$provider);
        }

        $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array()));
        $this->_view->render('paymentProviders/paymentComplete');
    }

    /**
     * Action handler for return success payment
     * @return void
     */
    public function successPaymentAction()
    {
        // set frontend settings
        Website::setFrontend();

        $this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('app', 'Thank You! Your order has been successfully completed.'), array()));
        $this->_view->render('paymentProviders/paymentComplete');
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
            $this->redirect('paymentProviders/manage');
        }
        return $paymentProvider;
    }
}
