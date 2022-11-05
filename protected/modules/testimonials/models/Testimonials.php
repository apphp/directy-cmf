<?php
/**
 * Testimonials model 
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct				_afterSave					_clearCache
 * model (static)			_afterDelete
 *
 */

namespace Modules\Testimonials\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig,
	\CFile,
	\CTime;

// Directy
use \LocalTime,
	\Website;


class Testimonials extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'testimonials';

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
	 * This method is invoked after saving a record successfully
	 * @param string $pk
	 */
	protected function _afterSave($id = 0)
	{
		$this->_clearCache();
	}	

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_clearCache();
	}
	
	/**
	 * Clear cache files
	 */
	protected function _clearCache()
	{
        if(CConfig::get('cache.enable')){
			// Delete cache file after BO updates
			$cacheFile = CConfig::get('cache.path').md5('testimonials-findall-5').'.cch';
			CFile::deleteFile($cacheFile);
		}
	}
}
