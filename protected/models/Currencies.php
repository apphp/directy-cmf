<?php
/**
 * Currencies model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct             	_beforeSave
 * model (static)		   	_afterSave
 * getError                	_customFields 
 * getDefaultCurrency      
 * getDefaultCurrencyInfo
 * drawSelector (static)
 *
 */

class Currencies extends CActiveRecord
{
    
    /** @var string */    
    protected $_table = 'currencies';
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
	 * Draws currencies selector
	 * @param array $params
	 *      'display' => 'names|keys|symbols|dropdown|list',
	 *      'class'	=> '',
	 *      'forceDrawing' => false,
	 * @return string - html code for currencies selector
	 */
	public static function drawSelector($params = array())
	{
        // Values: 'display' => 'names|keys|dropdown|list',
        $display = isset($params['display']) ? $params['display'] : 'names';
		$class = isset($params['class']) ? $params['class'] : '';
		$forceDrawing = isset($params['forceDrawing']) ? (bool)$params['forceDrawing'] : false;

        $arrCurrencies = array();
        $templateName = A::app()->view->getTemplate();
        $currencies = self::model()->findAll("is_active = 1");
        if(is_array($currencies)){
        	foreach($currencies as $curr){
	            $arrCurrencies[$curr['code']] = array('name'=>$curr['name'], 'symbol'=>$curr['symbol']);
	        }
        }
		
		$output = '';
        $output = CWidget::create('CCurrencySelector', array(
            'currencies' 		=> $arrCurrencies,
            'display' 			=> $display,
            'currentCurrency' 	=> A::app()->getCurrency(),
			'forceDrawing'		=> $forceDrawing,
			'class'				=> $class
        ));
		
        return $output;
	}

	/**
     * Used to define custom fields
	 * This method should be overridden
	 */
	protected function _customFields()
	{
		return array(
			'IF(symbol_place = "before", CONCAT(symbol_place, " (", symbol, 100, ")"), CONCAT(symbol_place, " (", 100, "", symbol, ")"))' => 'example_symbol_place'
		);
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
		// If currency is default - it must be active
		if($this->is_default) $this->is_active = 1;
		$this->code = strtoupper($this->code);
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	protected function _afterSave($id = 0)
	{
		$this->_isError = false;
		
		// If this currency is default - remove default flag in all other currencies
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
	 * Returns default currency
	 */
	public function getDefaultCurrency()
	{
        return ($currency = $this->find('is_default = 1')) ? $currency->code : '';
	}
	
	/**
	 * Returns default currency
	 */
	public function getDefaultCurrencyInfo()
	{
		return ($currency = $this->find('is_default = 1')) ? $currency : '';
	}
}
