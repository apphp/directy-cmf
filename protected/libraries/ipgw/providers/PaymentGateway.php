<?php
/**
 * PaymentGateway is the abstruct class that must be implemented by payment provider classes
 * 
 * PUBLIC (abstract):		PROTECTED:					PRIVATE:		        
 * ---------------         	---------------            	---------------
 * drawPaymentForm
 */

abstract class PaymentGateway
{
	/** @string */
	protected $_logMode;
	/** @string */
	protected $_logTo;
	/** @string */
	protected $_logData;
	/** @string */
	protected $_paymenModel;
	
	/**
	 * Draws payment form
	 */
	abstract public function drawPaymentForm();

	/**
	 * Handles payment
	 */
	abstract public function handlePayment($params);
	
	/**
	 * Handles payment
	 */
	protected function _paymenLog($msg = '')
	{		
		if($this->_logMode){
			$this->_logData .= '<br />'."\n".$msg;
		}    
	}
	
}
