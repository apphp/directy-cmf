<?php
/**
 * This file contains payment providers interfaces for ApPHP Directy CMF
 *
 * PUBLIC: (static)			PROTECTED:					PRIVATE: (static)
 * ---------------         	---------------            	---------------
 * init 												_classMap
 * 
 */	  

class PaymentProvider
{
	
	/** @var object */    
    private static $_instance;

    /** @var array */
    private static $_paymentClasses = array(
		'online_order'			=> array('class'=>'OnlineOrder', 'file'=>'OnlineOrder.php'),
		'online_credit_card'	=> array('class'=>'OnlineCreditCard', 'file'=>'OnlineCreditCard.php'),
		'wire_transfer'			=> array('class'=>'WireTransfer', 'file'=>'WireTransfer.php'),
        'paypal'				=> array('class'=>'PayPal', 'file'=>'PayPal.php'),
	);

	/**
	 * Initializes the database class
	 * @param string $provider
	 * @return class
	 */
	public static function init($provider = '')
	{
		if(self::$_instance == null){
			
			$providerInfo = self::_classMap($provider);
			$providerClass = $providerInfo['class'];
			$classFile = $providerInfo['file'];
			$classPath = $providerInfo['path'];
			
			if(file_exists($classPath.$classFile)){
				CLoader::library('PaymentGateway.php', 'ipgw/providers/');
				CLoader::library($classFile, 'ipgw/providers/');
				self::$_instance = new $providerClass();
			}else{
				CDebug::AddMessage('errors', 'payment-provider-missing-class', A::t('core', 'Unable to find class "{class}".', array('{class}'=>'libraries/ipgw/providers/'.$providerClass)));
			}			
		}
		
        return self::$_instance;    		
	}
	
	/**
	 * Detects class name and path
	 * @param string $provider
	*/
	private static function _classMap($provider)
	{
		$result = array();
		
		$provider = strtolower($provider);
		$result['class'] = isset(self::$_paymentClasses[$provider]['class']) ? self::$_paymentClasses[$provider]['class'] : '';
		$result['file'] = isset(self::$_paymentClasses[$provider]['file']) ? self::$_paymentClasses[$provider]['file'] : '';		
		$result['path'] = APPHP_PATH.DS.'protected'.DS.'libraries'.DS.'ipgw'.DS.'providers'.DS;
		
		return $result;		
	}

}
