<?php
/**
 * TicketsReply model
 *
 * PUBLIC:                 	PROTECTED:          		PRIVATE:
 * ---------------         	---------------           	---------------
 * __construct				_afterSave
 * model (static)			_customFields
 * 							_relations
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

class TicketReplies extends CActiveRecord
{
    /** @var string */
    protected $_table = 'tickets_replies';
	/** @var string */
    protected $_tableTikets = 'tickets';
	/** @var string */
	protected $_tableUserAccounts = '';
	/** @var string */
	protected $_tableAdminsAccounts = 'admins';
	/** @var string */
	protected $_memberRole = '';

    public function __construct()
    {
        parent::__construct();

		// Set members db table according to settings
		$configModule = CLoader::config('tickets', 'main');
		$this->_tableUserAccounts = isset($configModule['ticketMembers']['dbTable']) ? $configModule['ticketMembers']['dbTable'] : 'users_accounts';
		$this->_memberRole = isset($configModule['ticketMembers']['memberRole']) ? $configModule['ticketMembers']['memberRole'] : 'user';
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
				$this->_tableAdminsAccounts,
				'id',
				'joinType'=>self::LEFT_JOIN,
				'fields'=>array(
					'first_name'=>'first_name',
					'last_name'=>'last_name',
					'username'=>'username',
				)
			),
		);
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param int $pk
	 * You may override this method
	 */
	protected function _afterSave($pk = '')
	{
		$modelReplies = TicketReplies::model()->findByPk($pk);
		$modelTicket = Tickets::model()->findByPk($modelReplies->ticket_id);

		if($this->isNewRecord()){
			//If a file was uploaded, fill out the file_path
			if($modelReplies->file){
				$modelReplies->file_path = date('Y-m');
				$modelReplies->save();
			}
			if($modelReplies->account_role == 'admin'){
				// Status update on "Needs reply by User"
				$modelTicket->status = 2;
				$modelTicket->save();

				// Email on Admin Reply
				$fullname = $modelTicket->first_name.' '.$modelTicket->last_name;
				$sendTicketsNeedsReplyByUser = ModulesSettings::model()->param('tickets', 'email_on_admin_reply');
				if($sendTicketsNeedsReplyByUser){
					$emailResult = Website::sendEmailByTemplate(
						$modelTicket->email,
						'admin_reply',
						A::app()->getLanguage(),
						array('{FULL_NAME}' => $fullname, '{TICKET_ID}' => $modelTicket->id, '{CONTENT}' => $modelReplies->message, '{TOPIC}' => $modelTicket->topic)
					);
					if(!$emailResult){
						$arr[] = '"status": "1"';
						$arr[] = '"error": "'.A::t('tickets', 'Error').'"';
					}else{
						$arr[] = '"status": "1"';
					}
				}
			}elseif($modelReplies->account_role == $this->_memberRole){
				// Status update on "Needs reply by Admin"
				$modelTicket->status = 1;
				$modelTicket->save();

				// Email on User Reply
				$emailAdmin = ModulesSettings::model()->param('tickets', 'general_admin_email');
				$fullname = $modelTicket->first_name.' '.$modelTicket->last_name;
				$sendTicketsNeedsReplyByAdmin = ModulesSettings::model()->param('tickets', 'email_on_member_reply');
				if($sendTicketsNeedsReplyByAdmin){
					$emailResult = Website::sendEmailByTemplate(
						$emailAdmin,
						'member_reply',
						A::app()->getLanguage(),
						array('{FULL_NAME}' => $fullname, '{TICKET_ID}' => $modelTicket->id, '{EMAIL}' => $modelTicket->email, '{DEPT}' => $modelTicket->departments, '{CONTENT}' => $modelReplies->message)
					);
					if(!$emailResult){
						$arr[] = '"status": "1"';
						$arr[] = '"error": "'.A::t('tickets', 'Error').'"';
					}else{
						$arr[] = '"status": "1"';
					}
				}
			}
		}
	}

	/**
	 * Used to define custom fields
	 * This method should be overridden
	 */
	protected function _customFields()
	{
		return array(
			'CONCAT(first_name, " ", last_name)' => 'fullname_admin',
		);
	}

}