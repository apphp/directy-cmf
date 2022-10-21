<?php
/**
 * PaymentProviders controller
 *
 * PUBLIC:                  	PRIVATE:
 * ---------------          	---------------
 * __construct
 * indexAction
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

        // Set frontend mode
        Website::setFrontend();

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
		$this->redirect(Website::getDefaultPage());
    }

    /**
     * Test checkout action handler
     * Ex.: paymentProviders/testCheckout
     *
     * @param string $type
     */
    public function testCheckoutAction()
    {
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
     *      paymentProviders/testPayment?type=paypal or paymentProviders/testPayment?type=paypal_standard
     *      paymentProviders/testPayment?type=paypal_recurring
     *
     */
    public function testPaymentAction()
    {
        if(APPHP_MODE != 'debug'){
            $this->redirect(Website::getDefaultPage());
        }

        CLoader::library('ipgw/PaymentProvider.php');

        $type = $this->_cRequest->get('type');

        // PayPal Standard | PayPal Recurring |  | Online | OnlineCheckOut | Wire
        $type = (!empty($type) && in_array($type, array('online_order', 'online_credit_card', 'wire_transfer', 'paypal', 'paypal_standard', 'paypal_recurring'))) ? $type : 'paypal_standard';

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
            
            case 'paypal_recurring':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'paypal_recurring'");
                break;
            
            default:
            case 'paypal':
            case 'paypal_standard':
                $this->_view->providerSettings = PaymentProviders::model()->find("code = 'paypal_standard'");
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
     *      paymentProviders/handlePayment/online_order
     *
     * @param string $provider
     */
    public function handlePaymentAction($provider = '', $model = '', $module = '')
    {
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
        if($paymentProvider){            
            if(!empty($model)){            
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
                // TODO: Add here CMF logger???
                $alertType = 'error';
                $alert = (APPHP_MODE == 'debug' ? 'Model not found' : A::t('app', 'Payment Handeling Error'));
            }
        }else{
            // TODO: Add here CMF logger???
            $alertType = 'error';
            $alert = (APPHP_MODE == 'debug' ? 'Payment Provider not found' : A::t('app', 'Payment Handeling Error'));
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
        $this->_view->actionMessage = CWidget::create('CMessage', array('success', A::t('app', 'Thank You! Your order has been successfully completed.'), array()));
        $this->_view->render('paymentProviders/paymentComplete');
    }

}
