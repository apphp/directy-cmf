<?php
/**
 * Users model
 *
 * PUBLIC:                	PROTECTED:              	PRIVATE:
 * ---------------        	---------------         	---------------
 * __construct            	_relations
 * model (static)		  	_beforeSave
 * registrationSocial		_afterSave
 * getError               	_afterDelete
 * getErrorField            
 *
 */

namespace Modules\Users\Models;

// Framework
use \A,
	\CActiveRecord,
	\CAuth,
	\CModel,
	\CDatabase,
	\CConfig,
	\CHash;

// Directy
use \Accounts,
	\LocalTime,
	\ModulesSettings,
	\Website;


class Users extends Accounts
{

    /** @var string */    
    protected $_table = 'users_accounts';
    /** @var string */
    protected $_tableGroups = 'users_groups';
    /** @var string */
    protected $_tableAccounts = 'accounts';
    /** @var string */    
    protected $_role = 'user';

    /** @var string */    
    protected $_errorField = '';
    
    /** @var bool */    
    private $_sendApprovalEmail = false;
    /** @var bool */        
    private $_sendActivationEmail = false;
    /** @var bool */        
    private $_sendPasswordChangedEmail = false;


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
     * Defines relations between different tables in database and current $_table
	 */
	protected function _relations()
	{
		return array(
			'account_id' => array(
				self::HAS_ONE,
				$this->_tableAccounts,
				'id',
				'condition'=>"role = '".$this->_role."'",
				'joinType'=>self::INNER_JOIN,
				'fields'=>array(
					'role'=>'role',
                    'email'=>'email',
                    'language_code'=>'language_code',
                    'username'=>'username',
                    'created_at'=>'created_at',
                    'created_ip'=>'created_ip',
                    'last_visited_at'=>'last_visited_at',
                    'last_visited_ip'=>'last_visited_ip',
                    'notifications'=>'notifications',
                    'notifications_changed_at'=>'notifications_changed_at',
					'password_changed_at'=>'password_changed_at',
                    'is_active'=>'is_active',
                    'is_removed'=>'is_removed',
                    'comments'=>'comments'                    
                )
			),
			'group_id' => array(
				self::HAS_ONE,
				$this->_tableGroups,
				'id',
				'condition'=>'',
				'joinType'=>self::LEFT_OUTER_JOIN,
				'fields'=>array(
                    'name'=>'group_name',
                    'description'=>'group_description'
                )
			),
		);
	}

	/**
	 * This method is invoked before saving a record
	 * @param string $id
	 */
	protected function _beforeSave($id = 0)
	{
        $cRequest = A::app()->getRequest();
        $firstName = $cRequest->getPost('first_name');
        $lastName = $cRequest->getPost('last_name');
		$username = $cRequest->getPost('username');
		$password = $cRequest->getPost('password');
		$salt = $cRequest->getPost('salt');
        $email = $cRequest->getPost('email');
        $languageCode = $cRequest->getPost('language_code', 'alpha', A::app()->getLanguage());
        $notifications = (int)$cRequest->getPost('notifications', 'int');
        if($notifications !== 0 && $notifications !== 1) $notifications = 0;  
        $isActive = (int)$cRequest->getPost('is_active', 'int');
        $isRemoved = (int)$cRequest->getPost('is_removed', 'int');
        $comments = $cRequest->getPost('comments');
        $ipAddress = $cRequest->getUserHostAddress();
        $approvalType = ModulesSettings::model()->param('users', 'approval_type');
        $changePassword = ModulesSettings::model()->param('users', 'change_user_password');
        
        if(CConfig::get('password.encryption')){
            $encryptAlgorithm = CConfig::get('password.encryptAlgorithm');
			$encryptSalt = $salt;
            if(!empty($password)){
                $password = CHash::create($encryptAlgorithm, $password, $encryptSalt);
            }							
        }

		// Check if user with the same email already exists
		$userExists = $this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').'accounts WHERE role = :role AND email = :email AND id != :id', array(':role'=>$this->_role, ':email'=>$email, ':id'=>$this->account_id));
		if(!empty($email) && $userExists){
			$this->_error = true;
			$this->_errorMessage = A::t('users', 'User with such email already exists!');
			$this->_errorField = 'email';
			return false;            
		}

        if($id > 0){
            // UPDATE ACCOUNT
            // Update accounts table
            $account = Accounts::model()->findByPk((int)$this->account_id);
            if(CAuth::isLoggedInAsAdmin()){            
                $account->comments = $comments;
                $account->is_active = $isActive;
                $account->is_removed = $isRemoved;
                // Logical deleting
                if($isRemoved == 1){
                    $account->is_active = 0;
                }
                
                // Approval by admin (previously created by user)
                if($approvalType == 'by_admin' && $account->registration_code != '' && $isActive){                    
                    $account->registration_code = '';
                    $this->_sendApprovalEmail = true;
                }
                
                // Password changed by admin
                if($changePassword && $account->password != $password && !empty($password) && $isActive){                    
                    $this->_sendPasswordChangedEmail = true;
                }
            }
			
			// Password was changed
			if($password !== ''){
				$account->password_changed_at = LocalTime::currentDateTime();
			}

            if(!empty($password)) $account->password = $password;
			if(!empty($salt)) $account->salt = $salt;
            $account->email = $email; 
            $account->language_code = $languageCode; 
            if($account->notifications != $notifications){
                $account->notifications = $notifications;
                $account->notifications_changed_at = LocalTime::currentDateTime();
            }

            if($account->save()){
                // Update existing user
                if($this->birth_date == '') $this->birth_date = null;
                if($this->group_id == '') $this->group_id = '0';                
                return true;
            }            
            return false;
        }else{
            // NEW ACCOUNT
            // Check if user with the same username already exists
            if($this->_db->select('SELECT * FROM '.CConfig::get('db.prefix').'accounts WHERE role = :role AND username = :username', array(':role'=>$this->_role, ':username'=>$username))){
                $this->_error = true;
                $this->_errorMessage = A::t('users', 'User with such username already exists!');
                $this->_errorField = 'username';
                return false;            
            }
            
            // Insert new user
            if($accountId = $this->_db->insert($this->_tableAccounts, array(
                'role'=>$this->_role,
                'username'=>$username,
                'password'=>$password,
				'salt'=>$salt,
                'email'=>$email,
                'language_code'=>$languageCode,
				///'avatar' => $this->getSpecialField('avatar'),
                'created_at'=>LocalTime::currentDateTime(),
                'created_ip'=>$ipAddress,
                'notifications'=>$notifications,
                'registration_code'=>'',
                'is_active'=>$isActive,
                'comments'=>$comments
            ))){                
                $this->account_id = $accountId;
                if($this->birth_date == '') $this->birth_date = null;
                if($this->group_id == '') $this->group_id = '0';
                
                // Account activated by admin (previously created by admin)                
                if(CAuth::isLoggedInAsAdmin() && $isActive){
                    $this->_sendActivationEmail = true;
                }
                return true;
            }
            return false;
        }
	}

	/**
	 * This method is invoked after saving a record successfully
	 * @param string $pk
	 * You may override this method
	 */
	protected function _afterSave($pk = '')
	{
        $cRequest = A::app()->getRequest();
        $email = $cRequest->getPost('email');
        $firstName = $cRequest->getPost('first_name');
        $lastName = $cRequest->getPost('last_name');
        $username = $cRequest->getPost('username', '', $this->username);
        $password = $cRequest->getPost('password');
        $languageCode = $cRequest->getPost('language_code');
        $isActive = (int)$cRequest->getPost('is_active', 'int');
        
        if($firstName == '' && $lastName == ''){
            $lastName = A::t('users', 'user');
        }

        // Send email to user on creating new account by admininstrator (if user is active)
        if($this->_sendActivationEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'users_account_created_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName,
                    '{USERNAME}' => $username,
                    '{PASSWORD}' => $password,
                )
            );                                    
        }

        // Send email to user on admin changes user password
        if($this->_sendPasswordChangedEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'users_password_changed_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName,
                    '{USERNAME}' => $username,
                    '{PASSWORD}' => $password,
                )
            );
        }

        // Send email to user on admin approval
        if($this->_sendApprovalEmail){
            $result = Website::sendEmailByTemplate(
                $email,
                'users_account_approved_by_admin',
                $languageCode,
                array(
                    '{FIRST_NAME}' => $firstName,
                    '{LAST_NAME}' => $lastName
                )
            );                                    
        }
	}

	/**
	 * This method is invoked after deleting a record successfully
	 * @param string $pk
	 */
	protected function _afterDelete($pk = '')
	{
		// delete record from accounts table
		if(false === $this->_db->delete($this->_tableAccounts, 'id = '.(int)$this->account_id)){
			$this->_error = true;
            $this->_errorMessage = A::t('users', 'An error occurred while deleting user account! Please try again later.');
		}
	}

    /*
     * Social Registration
     * @param array $params
     * @return void
     */
    public function registrationSocial($params = array())
    {
        $this->username     = isset($params['username']) ? $params['username'] : '';
        $this->email        = isset($params['email']) ? $params['email'] : '';
        $this->first_name   = isset($params['firstName']) ? $params['firstName'] : '';
        $this->last_name    = isset($params['lastName']) ? $params['lastName'] : '';
    }

	/**
	 * Returns error description
	 * @return boolean
	 */
	public function getError()
	{
		return $this->_errorMessage;
	}
    
	/**
	 * Returns error field
	 * @return boolean
	 */
	public function getErrorField()
	{
		return $this->_errorField;
	}
      
}
