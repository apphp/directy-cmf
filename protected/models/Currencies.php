<?php
/**
 * Currencies model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * beforeSave
 * afterSave
 * afterDelete
 * getError
 * getDefaultCurrency
 * getDefaultCurrencyInfo
 *
 * STATIC:
 * ------------------------------------------
 * model
 *
 */
class Currencies extends CActiveRecord
{
    
    /** @var string */    
    protected $_table = 'currencies';
    
    private $isError = false;
    
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
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

  	/**
	 * Draws currencies selector
	 * @return string - html code for currencies selector
	 */
	public static function drawSelector()
	{
        $arrCurrencies = array();
        $templateName = A::app()->view->getTemplate();
        $currencies = self::model()->findAll("is_active = 1");
        if(is_array($currencies)){
        	foreach($currencies as $curr){
	            $arrCurrencies[$curr['code']] = array('name'=>$curr['name']);
	        }
        }
		
		$output = '';
        $output = CWidget::create('CCurrencySelector', array(
            'currencies' => $arrCurrencies,
            'display' => 'code',
            'currentCurrency' => A::app()->getCurrency(),
        ));
		
        return $output;
	}

	/**
     * Used to define custom fields
	 * This method should be overridden
	 */
	public function customFields()
	{
		return array(
			'IF(symbol_place = "before", CONCAT(symbol_place, " (", symbol, 100, ")"), CONCAT(symbol_place, " (", 100, "", symbol, ")"))' => 'example_symbol_place'
		);
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	public function beforeSave($id = 0)
	{
		// if currency is default - it must be active
		if($this->is_default) $this->is_active = 1;
		$this->code = strtoupper($this->code);
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $id
	 */
	public function afterSave($id = 0)
	{
		$this->isError = false;
		
		// if this currency is default - remove default flag in all other currencies
		if($this->is_default){
        	if(!$this->db->update($this->_table, array('is_default'=>0), 'id != '.$id)){
        		$this->isError = true;
        	}
		}		
	}
	
	/** 
	 * Returns boolean that indicates if the last operation was successfull
	 * @return boolean
	 */
	public function getError()
	{
		return $this->isError;
	}

	/**
	 * Returns default currency
	 */
	public function getDefaultCurrency()
	{
		$currency = $this->find('is_default = 1');
		return isset($currency[0]['code']) ? $currency[0]['code'] : '';
	}
	
	/**
	 * Returns default currency
	 */
	public function getDefaultCurrencyInfo()
	{
		$currency = $this->find('is_default = 1');
		return isset($currency[0]) ? $currency[0] : '';
	}
}
