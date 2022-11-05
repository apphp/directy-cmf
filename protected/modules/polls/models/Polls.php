<?php
/**
 * Template of Polls model
 *
 * PUBLIC:                  PROTECTED                  PRIVATE
 * ---------------          ---------------            ---------------
 * __construct              _relations
 * model (static)           _customFields
 * isClosed                 _beforeSave
 *                          _afterDelete
 *
 */

namespace Modules\Polls\Models;

// Framework
use \A,
	\CActiveRecord,
	\CModel,
	\CDatabase,
	\CConfig,
	\CTime;

// Directy
use \LocalTime,
	\Website;


class Polls extends CActiveRecord
{
    /** @var string */
    protected $_table = 'polls';

    /** @var string */
    protected $_tableTranslation = 'polls_translations';

    /** @var array */
    protected $_columnsTranslation = array(
        'poll_question',
        'poll_answer_1',
        'poll_answer_2',
        'poll_answer_3',
        'poll_answer_4',
        'poll_answer_5',
    );

    /** @var array */
    private $_translationsArray;

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
     * Used to define custom fields
     * This method should be overridden
     * Usage: 'CONCAT(last_name, " ", first_name)' => 'fullname'
     *        '(SELECT COUNT(*) FROM '.CConfig::get('db.prefix').$this->_tableTranslation.')' => 'records_count'
     */
    protected function _customFields()
    {
        return array(
            '(poll_answer_1_votes + poll_answer_2_votes + poll_answer_3_votes + poll_answer_4_votes + poll_answer_5_votes)' => 'total_votes',
        );
    }

    /**
     * Defines relations between different tables in database and current $_table
     */
    protected function _relations()
    {
        return array(
            'id' => array(
                self::HAS_MANY,
                $this->_tableTranslation,
                'polls_id',
                'condition' => "language_code = '" . A::app()->getLanguage() . "'",
                'joinType' => self::LEFT_OUTER_JOIN,
                'fields' => array(
                    'poll_question' => 'poll_question',
                    'poll_answer_1' => 'poll_answer_1',
                    'poll_answer_2' => 'poll_answer_2',
                    'poll_answer_3' => 'poll_answer_3',
                    'poll_answer_4' => 'poll_answer_4',
                    'poll_answer_5' => 'poll_answer_5',
                )
            ),
        );
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

        // Close poll if it's date has expired
        if(!CTime::isEmptyDate($this->expires_at) && $this->expires_at <= LocalTime::currentDate()){
            $this->is_active = 0;
        }

        return true;
    }

    /**
     * This method is invoked after deleting a record successfully
     * @param string $id
     */
    protected function _afterDelete($id = 0)
    {
        $this->_isError = false;
        // Delete polls from translation table
        if(false === $this->_db->delete($this->_tableTranslation, 'polls_id = :polls_id', array(':polls_id'=>$id))){
            $this->_isError = true;
        }
    }

    /**
     * Whether polling is already closed
     * @return boolean
     */
    public function isClosed()
    {
        $finishedTime = strtotime($this->expires_at);

        if(empty($finishedTime) || $finishedTime > LocalTime::currentDate()){
            return false;
        }

        return true;
    }
}
