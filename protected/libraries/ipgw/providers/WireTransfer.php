<?php
/**
 * This file implements Wire Transfer payment provider functionality
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		        
 * ---------------         	---------------            	---------------
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
		$output = '';
		
		return $output;
	}
	
	/**
	 * Handles payment
	 * @param array $params
	 */
	public function handlePayment($params = array())
	{
        $return = array('error' => 0, 'message' => '', 'order' => array());

		return $return;
	}

}

