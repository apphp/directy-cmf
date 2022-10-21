<?php
/**
 * Currencies model
 *
 * PUBLIC:                 		PROTECTED:                 	PRIVATE:
 * ---------------         		---------------            	---------------
 * __construct             		_customFields				_clearCache
 * model (static)		   		_beforeSave
 * getError                		_afterSave
 * getDefaultCurrency      		_afterDelete
 * getDefaultCurrencyInfo
 * drawSelector (static)
 * countCurrencies
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
	/** @var float */
	private $_previousRate = 1;
	/** @var string */
	private static $_cacheKeyFindAll = '';
	/** @var string */
	private static $_cacheKeyCount = '';

    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

		self::$_cacheKeyFindAll = md5('Frontend|Currencies|findAll');
		self::$_cacheKeyCount = md5('Frontend|Currencies|Count');
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
		$condition = 'is_active = 1';
		$orderBy = 'sort_order ASC';

        // Take data from session cache or from database if not exists
		$arrCurrencies = CSessionCache::get(self::$_cacheKeyFindAll);
		if(empty($arrCurrencies)){
			$currencies = self::model()->findAll(array('condition'=>$condition, 'orderBy'=>$orderBy));
			if(is_array($currencies)){
				foreach($currencies as $curr){
					$arrCurrencies[$curr['code']] = array('name'=>$curr['name'], 'symbol'=>$curr['symbol']);
				}
			}
			CSessionCache::set(self::$_cacheKeyFindAll, $arrCurrencies);
		}

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
	 * Count currencies
	 * @return int
	 */
	public static function countCurrencies()
	{
		// Take data from session cache or from database if not exists
		$countCurrencies = CSessionCache::get(self::$_cacheKeyCount);
		if(empty($countCurrencies)){
			$countCurrencies = Currencies::model()->count(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'));
			CSessionCache::set(self::$_cacheKeyCount, $countCurrencies);
		}

		return $countCurrencies;
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
			$alert = '';
			foreach($currencies as $key => $currency){
				$url = 'https://x-rates.com/calculator/?from='.$defaultCurrency['code'].'&to='.$currency['code'].'&amount=1';
				$data = @file_get_contents($url);
				$data = explode('<span class="ccOutputRslt">', $data);
				if(!empty($data[0]) && !empty($data[1])){
					$data = explode('<span', $data[1]);
					$converted_amount = $data[0];
					$converted_amount = round($converted_amount, 4);
				
					if(!empty($converted_amount) && $currency['rate'] != $converted_amount){					
						Currencies::model()->updateByPk($currency['id'], array('rate'=>$converted_amount, 'updated_at'=>LocalTime::currentDateTime()));
					}
				
					$alert .= '<br>'.$currency['code'].' : '.A::t('app', 'previous').' - '.$currency['rate'].' / '.A::t('app', 'new rate').' - '.($currency['rate'] != $converted_amount ? '<b>'.$converted_amount.'</b>' : A::t('app', 'not changed'));
				}
			}
			
			$result['alert'] = A::t('app', 'Currency rates have been successfully updated!').$alert;
			$result['alertType'] = 'success';

			$lastError = error_get_last();
			if(!empty($lastError['message'])){
				$result['alert'] = $lastError['message'];
				$result['alertType'] = 'error';
			}
			
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
		if($this->is_default){
			$this->_previousRate = $this->rate;
			$this->is_active = 1;
			$this->rate = 1;
		}

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
		
		// If this currency is default - remove default flag in other currencies
		if($this->is_default){
            if(!$this->_db->update($this->_table, array('is_default'=>0, 'rate'=>array('expression'=>'ROUND(rate/'.$this->_previousRate.',4)')), 'id != :id', array(':id'=>(int)$id))){
        		$this->_isError = true;
        	}
		}		

		// Clear cache
		$this->_clearCache();
	}
	
	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $pk
	 */
	protected function _afterDelete($pk = 0)
	{
		$this->_isError = false;

		// Clear cache
		$this->_clearCache();
	}

	/**
	 * This method clears all cache related to languages
	 * @return void
	 */
	private function _clearCache()
	{
		CSessionCache::remove(self::$_cacheKeyFindAll);
		CSessionCache::remove(self::$_cacheKeyCount);
	}

}
