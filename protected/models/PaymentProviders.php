<?php
/**
 * PaymentProviders model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_beforeSave
 * model (static)		   	_afterSave
 * getError                	_customFields 
 * getDefaultCurrency      
 * getDefaultCurrencyInfo
 *
 */

class PaymentProviders extends CActiveRecord
{
    
    /** @var string */    
    protected $_table = 'payment_providers';
    /** @var bool */
    private $_isError = false;
    
    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
		// If payment provider is default - it must be active
		if($this->is_default) $this->is_active = 1;
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		
		// If this payment provider is default - remove default flag in all other currencies
		if($this->is_default){
            if(!$this->_db->update($this->_table, array('is_default'=>0), 'id != :id', array(':id'=>(int)$id))){    
        		$this->_isError = true;
        	}
		}		
	}
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_isError;
	}

	/**
	 * Returns default payment provider
	 * @return int
	 */
	public function getDefaultPaymentProvider()
	{
        return ($paymentProvider = $this->find('is_default = 1')) ? $paymentProvider->id : 0;
	}
	
	/**
	 * Returns default payment provider info
	 * @return array
	 */
	public function getDefaultPaymentProviderInfo()
	{
		return ($paymentProvider = $this->find('is_default = 1')) ? $paymentProvider : '';
	}
}
