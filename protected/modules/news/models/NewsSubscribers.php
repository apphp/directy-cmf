<?php
/**
 * Newsletter model
 *
 * PUBLIC:                  	PROTECTED:                  PRIVATE:
 * ---------------          	---------------             ---------------
 * __construct					
 * model (static)				
 * 
 */

namespace Modules\News\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig;

class NewsSubscribers extends CActiveRecord
{

    /** @var string */
    protected $_table = 'news_subscribers';
    
    /** @var string */
    protected $_errorField = '';
    
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the static model of the specified AR class
     * @return current class
     */
    public static function model()
    {
        return parent::model(__CLASS__);
    }

}
