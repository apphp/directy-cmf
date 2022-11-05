<?php
/**
 * Template of Advertisements model
 *
 * PUBLIC:                  PROTECTED                  PRIVATE
 * ---------------          ---------------            ---------------
 * __construct              _relations
 * model (static)           _afterDelete
 *
 */

namespace Modules\Advertisements\Models;

// Framework
use \A,
	\CActiveRecord,
    \CFile;

// CMF
use \LocalTime;


class Advertisements extends CActiveRecord
{
    /** @var string */
    protected $_table = 'ads';

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
     * Defines relations between different tables in database and current $_table
     */
    protected function _relations()
    {
        return array();
    }

    /**
     * This method is invoked before saving a record (after validation, if any)
     * @param int $pk
     * @return boolean
     */
    protected function _beforeSave($pk = '')
    {
        if (!$pk) {
            $this->created_at = LocalTime::currentDate();
        }

        return true;
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param int $pk
     * You may override this method
     */
    protected function _afterDelete($pk = '')
    {
		$imagePath = 'assets/modules/advertisements/images/items/';
		$imageName = $this->image;
		$imageFullPath = $imagePath.$imageName;
		// Delete the main_image
		if(!CFile::deleteFile($imageFullPath)){
			$this->_error = true;
			$this->_errorMessage = A::t('advertisements', 'Image Delete Warning');
		}
    }
}
