<?php
/**
 * WebformsMessages model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------				---------------
 * __construct
 * model (static)
 *
 *
 */

namespace Modules\Webforms\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class WebformsMessages extends CActiveRecord
{

    /** @var string */
    protected $_table = 'webforms_messages';

    private $_translationsArray;
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

}
