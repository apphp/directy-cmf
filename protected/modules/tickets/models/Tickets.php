<?php
/**
 * Test model
 *
 * PUBLIC:                 	PROTECTED:          		PRIVATE:
 * ---------------         	---------------           	---------------
 * __construct             	_relations
 * model (static)          	_customFields
 * 							_afterSave
 * 							_afterDelete
 *
 */

namespace Modules\Tickets\Models;

// Framework
use \A,
	\CActiveRecord,
	\CLoader,
	\CModel,
	\CDatabase,
	\CConfig;

// Directy
use \ModulesSettings,
	\Website;


class Tickets extends CActiveRecord
{
	/** @var string */
	protected $_table = 'tickets';
	/** @var string */
	protected $_tableReplies = 'tickets_replies';
	/** @var string */
	protected $_tableUserAccounts = '';
	/** @var string */
	protected $_roleUser = '';

	public function __construct()
	{
		parent::__construct();

		// Set members db table according to settings
		$configModule = CLoader::config('tickets', 'main');
		$this->_tableUserAccounts = isset($configModule['ticketMembers']['dbTable']) ? $configModule['ticketMembers']['dbTable'] : 'users_accounts';
		$this->_roleUser = isset($configModule['ticketMembers']['memberRole']) ? $configModule['ticketMembers']['memberRole'] : 'user';
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
		return array(
			'account_id' => array(
				self::HAS_ONE,
				$this->_tableUserAccounts,
				'account_id',
				'condition'=>"account_role = '".$this->_roleUser."'",
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array(
					'first_name' => 'first_name',
					'last_name' => 'last_name',
				)
			),
		);
	}

	/**
     * Used to define custom fields
	 * This method should be overridde
	 */
	protected function _customFields()
	{
        return array(
        	'(SELECT date_created FROM '.CConfig::get('db.prefix').$this->_tableReplies.' WHERE ticket_id = '.CConfig::get('db.prefix').$this->_table.'.id ORDER BY date_created DESC LIMIT 0,1)'
            =>
            'last_answer_date',
			'CONCAT(first_name, " ", last_name)' => 'fullname_user',
        );
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param int $pk
	 * You may override this method
	 */
	protected function _afterSave($pk = '')
	{

		$modelTicket = Tickets::model()->findByPk($pk);

		if($this->isNewRecord()){
			// If a file was uploaded, fill out the file_path
			if($modelTicket->file){
				$modelTicket->file_path = date('Y-m');
				$modelTicket->save();
			}

			//Email on New Ticket
			$emailAdmin = ModulesSettings::model()->param('tickets', 'general_admin_email');
			$fullname = $modelTicket->first_name.' '.$modelTicket->last_name;
			$departmentsTicket = array (
				'0' => A::t('tickets', 'General'),
				'1' => A::t('tickets', 'Technical Issues'),
				'2' => A::t('tickets', 'Billing'),
				'3' => A::t('tickets', 'Security'),
			);

			$sendTicketsOpened = ModulesSettings::model()->param('tickets', 'email_on_new_ticket');
			if($sendTicketsOpened){
				$emailResult = Website::sendEmailByTemplate(
					$emailAdmin,
					'new_ticket',
					A::app()->getLanguage(),
					array('{FULL_NAME}' => $fullname, '{TICKET_ID}' => $modelTicket->id, '{EMAIL}' => $modelTicket->email, '{DEPT}' => $departmentsTicket[$modelTicket->departments], '{CONTENT}' => $modelTicket->message)
				);
				if(!$emailResult){
					$arr[] = '"status": "1"';
					$arr[] = '"error": "'.A::t('tickets', 'Error').'"';
				}else{
					$arr[] = '"status": "1"';
				}
			}
		}elseif($modelTicket->status == 3){
			//Email on Closing Ticket
			$fullname = $modelTicket->first_name.' '.$modelTicket->last_name;
			$sendTicketsClosed = ModulesSettings::model()->param('tickets', 'email_on_closing_ticket');
			if($sendTicketsClosed){
				$emailResult = Website::sendEmailByTemplate(
					$modelTicket->email,
					'closing_ticket',
					A::app()->getLanguage(),
					array('{FULL_NAME}' => $fullname, '{TICKET_ID}' => $modelTicket->id, '{TOPIC}' => $modelTicket->topic)
				);
				if(!$emailResult){
					$arr[] = '"status": "1"';
					$arr[] = '"error": "'.A::t('tickets', 'Error').'"';
				}else{
					$arr[] = '"status": "1"';
				}
			}
		}elseif($_POST['close_ticket'] == true){
            $modelTicket->status = 3;
            $modelTicket->save();
        }
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $id
	 */
	protected function _afterDelete($id = 0)
	{
		$this->_isError = false;
		// Delete from ticket replies table
		if(false === $this->_db->delete($this->_tableReplies, "ticket_id = '".$id."'")){
			$this->_isError = true;
		}
	}
    
}
