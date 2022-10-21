<?php
/**
 * CRecordEntity base class for classes that represent a single database row.
 * It implements the Active Record design pattern.
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2019 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 *
 * PUBLIC:					PROTECTED:					PRIVATE:
 * ---------------         	---------------            	---------------
 * __construct
 * __set
 * __get
 * __unset
 * set
 * get
 * primaryKey
 * getPrimaryKey
 * setPrimaryKey
 * columns
 *
 */

abstract class CRecordEntity
{
	/**	@var */
	protected $_columns = array();
	/**	@var */
	protected $_primaryKey = '';
	/**	@var */
	protected $_pkValue = 0;
	
	
	/**
	 * Class constructor
	 * @param int $pkVal
	 */
	public function __construct($pkVal = 0)
	{
		if(!empty($pkVal)){
			$this->_pkValue = $pkVal;
		}
	}
	
	/**
	 * Setter
	 * @param string $index
	 * @param mixed $value
	 * @return void
	 */
	public function __set($index, $value)
	{
		$this->_columns[$index] = $value;
	}
	
	/**
	 * Getter
	 * @param string $index
	 * @return string
	 */
	public function __get($index)
	{
		if(array_key_exists($index, $this->_columns)){
			return $this->_columns[$index];
		}else{
			CDebug::AddMessage('errors', 'wrong_column'.$index, A::t('core', 'Wrong column name: {index} in table {table}', array('{index}'=>$index, '{table}'=>__CLASS__)));
			return '';
		}
	}
	
	/**
	 * Sets a active record property to be null
	 * @param string $name
	 * @return void
	 */
	public function __unset($index)
	{
		if(array_key_exists($index, $this->_columns)){
			unset($this->_columns[$index]);
		}
	}
	
	/**
	 * Setter
	 * @param string $index
	 * @param mixed $value
	 * @return void
	 */
	public function set($index, $value)
	{
		$this->_columns[$index] = $value;
	}
	
	/**
	 * Getter
	 * @param string $index
	 * @return string
	 */
	public function get($index)
	{
		if(array_key_exists($index, $this->_columns)){
			return $this->_columns[$index];
		}else{
			CDebug::AddMessage('errors', 'wrong_column'.$index, A::t('core', 'Wrong column name: {index} in table {table}', array('{index}'=>$index, '{table}'=>$this->_table)));
			return '';
		}
	}
	
	/**
	 * Returns the primary key of the associated database table
	 * @return string
	 */
	public function primaryKey()
	{
		return $this->_primaryKey;
	}
	
	/**
	 * Returns the primary key value
	 * @return mixed
	 */
	public function getPrimaryKey()
	{
		return $this->_pkValue;
	}
	
	/**
	 * Returns the primary key value
	 * @param int $pkVal
	 */
	protected function setPrimaryKey($pkVal = 0)
	{
		if(!empty($pkVal)){
			$this->_pkValue = $pkVal;
		}
	}
	
	/**
	 * Return all columns
	 * @return array
	 */
	public function columns()
	{
		return $this->_columns;
	}
}