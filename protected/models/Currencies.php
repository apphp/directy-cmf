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
 * cronJob (static)
 * updateRates (static)
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
	 * Cronjob for currency rates
	 */
	public static function cronJob()
	{
		// Uncomment if you want to run this method with CronJob
		//self::updateRates();
	}
	
	/**
	 * Update currency rates
	 * @return array
	 */
	public static function updateRates()
	{
		$result = array('alert'=>'', 'alertType'=>'');
		
		$currencies = Currencies::model()->findAll(array('order' => 'is_default DESC'));
		if(!empty($currencies)){
			$defaultCurrency = array_shift($currencies);
			foreach($currencies as $key => $currency){
				$url = 'https://finance.google.com/finance/converter?a=1&from='.$defaultCurrency['code'].'&to='.$currency['code'];
				$data = file_get_contents($url);
				$data = explode('<span class=bld>', $data);
				$data = explode('</span>', $data[1]);
				$converted_amount = $data[0];
				$converted_amount = round($converted_amount, 4);
				
				if(!empty($converted_amount) && $currency['rate'] != $converted_amount){					
					Currencies::model()->updateByPk($currency['id'], array('rate'=>$converted_amount, 'updated_at'=>LocalTime::currentDateTime()));
				}
				
				$alert .= '<br>'.$currency['code'].' : '.A::t('app', 'previous').' - '.$currency['rate'].' / '.A::t('app', 'new rate').' - '.($currency['rate'] != $converted_amount ? '<b>'.$converted_amount.'</b>' : A::t('app', 'not changed'));
			}
			
			$result['alert'] = A::t('app', 'Currency rates have been successfully updated!').$alert;
			$result['alertType'] = 'success';
		}
		
		return $result;
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
		$this->updated_at = LocalTime::currentDateTime();
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
	
}
