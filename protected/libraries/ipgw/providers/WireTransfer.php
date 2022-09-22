<?php
/**
 * This file implements Wire Transfer payment provider functionality
 *
 * Usage:
 *
 * CLoader::library('ipgw/PaymentProvider.php');
 * $onlineOrder = PaymentProvider::init('online_order');
 * echo $onlineOrder->drawPaymentForm(array(
 *      'merchant_id'   => 'sales@email.me',
 *      'item_name'     => 'Item Name',
 *      'item_number'   => 'Item Number',
 *      'amount'        => 9.90,
 *      'custom'        => '',      // order ID
 *      'currency_code' => 'USD',   // The currency of the payment. The default is USD.
 *      'no_shipping'   => '',      // Do not prompt buyers for a shipping address.
 *      'address1'      => 'st. Big Street, 1',
 *      'address2'      => '',
 *      'city'          => 'Ney York',
 *      'zip'           => '1001',
 *      'state'         => 'Ney York',
 *      'country'       => 'us',
 *      'first_name'    => 'John',
 *      'last_name'     => 'Smith',
 *      'email'         => 'j.smith@email.me',
 *      'phone'         => '12345678',
 *
 *      'mode'          => 1,       // 1- Real mode, 0 - Test mode
 *      'notify'        => A::app()->getRequest()->getBaseUrl().'/paymentProviders/handlePayment/wire_transfer',    //
 *      'cancel'        => A::app()->getRequest()->getBaseUrl().'/paymentProviders/testCheckout',           // Cancel order link
 *      'cancel_return' => A::app()->getRequest()->getBaseUrl().'/paymentProviders/testCheckout',           // Cancel & return to site link
 *      'back'          => '',      // Back to Shopping Cart - defined by developer
 * ));
 *
 *
 *
 * PUBLIC:                  PROTECTED:                  PRIVATE:
 * ---------------          ---------------             ---------------
 * drawPaymentForm
 * handlePayment
 *
 */

class WireTransfer extends PaymentGateway
{
    /** @str */
    const NL = "\n";

    /**
     * Draws payment form
     * @param array $params
     */
    public function drawPaymentForm($params = array())
    {
        $output             = '';

        $mode               = isset($params['mode']) ? $params['mode'] : 1;

        $itemName           = isset($params['item_name']) ? $params['item_name'] : '';
        $itemNumber         = isset($params['item_number']) ? $params['item_number'] : '001';
        $amount             = isset($params['amount']) ? $params['amount'] : '0';
        $custom             = isset($params['custom']) ? $params['custom'] : '';
        $currencyCode       = isset($params['currency_code']) ? $params['currency_code'] : 'USD';
        $noShipping         = isset($params['no_shipping']) ? $params['no_shipping'] : '1';

        $address1           = isset($params['address1']) ? $params['address1'] : '';
        $address2           = isset($params['address2']) ? $params['address2'] : '';
        $city               = isset($params['city']) ? $params['city'] : '';
        $zip                = isset($params['zip']) ? $params['zip'] : '';
        $state              = isset($params['state']) ? $params['state'] : '';
        $country            = isset($params['country']) ? $params['country'] : '';

        $firstName          = isset($params['first_name']) ? $params['first_name'] : '';
        $lastName           = isset($params['last_name']) ? $params['last_name'] : '';
        $email              = isset($params['email']) ? $params['email'] : '';
        $phone              = isset($params['phone']) ? $params['phone'] : '';

        $notifyUrl          = isset($params['notify']) ? $params['notify'] : '';
        $returnUrl          = isset($params['return']) ? $params['return'] : '';
        $cancelReturnUrl    = isset($params['cancel_return']) ? $params['cancel_return'] : '';
        $backUrl            = isset($params['back']) ? $params['back'] : '';
        $formAction         = $notifyUrl;

        $output .= CHtml::openForm($formAction, 'post', array('name'=>'payform')).self::NL;

        $output .= '<input type="submit" value="'.A::te('app', 'Pay Now').'" name="btnSubmit" />'.self::NL;

        if((!empty($backUrl))){
            $output .= '&nbsp; - '.A::t('app', 'or').' - &nbsp;'.self::NL;
            $output .= (!empty($backUrl)) ? '<a href="'.$backUrl.'">'.A::t('app', 'Back').'</a>' : '';
        }

        $output .= CHtml::closeForm().self::NL;

        return $output;
    }

    /**
     * Handles payment
     * @param array $params
     */
    public function handlePayment($params = array())
    {
        $return = array('error' => 0, 'message' => '', 'order' => array());

        $this->_logMode = !empty($params['log']) ? (bool)$params['log'] : false;
        // 'file' - default or 'screen'
        $this->_logTo = 'file';
        $this->_logData = '';

        $cRequest = A::app()->getRequest();

        $nl         = "\n";
        $msg        = '';
        $logData    = '';

        // Start log data
        // ---------------------------------------------------------------------
        if($this->_logMode){
            if($this->_logTo == 'file'){
                $myFile = 'protected/tmp/logs/payments.log';
                $fh = fopen($myFile, 'a') or die('can\'t open file');
            }

            $logData .= $nl.$nl.'= ['.date('Y-m-d H:i:s').'] ============='.$nl;
            $logData .= '<br />---------------<br />'.$nl;
            $logData .= 'POST<br />'.$nl;
            foreach($_POST as $key=>$value) {
                $logData .= $key.'='.$value.'<br />'.$nl;
            }
            $logData .= '<br />---------------<br />'.$nl;
            $logData .= 'GET<br />'.$nl;
            foreach($_GET as $key=>$value) {
                $logData .= $key.'='.$value.'<br />'.$nl;
            }
        }
        // ---------------------------------------------------------------------

        // Save log data
        // ---------------------------------------------------------------------
        if($this->_logMode){
            $logData .= '<br />'.$nl.$msg.'<br />'.$nl;
            if($this->_logTo == 'file'){
                fwrite($fh, strip_tags($logData));
                fclose($fh);
            }else{
                echo $logData;
            }
        }
        // ---------------------------------------------------------------------

        return $return;
    }

}

