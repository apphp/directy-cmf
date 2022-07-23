<?php
/**
 * Accounts model
 *
 * PUBLIC:                 PROTECTED                  PRIVATE
 * ---------------         ---------------            ---------------
 * __construct
 * model
 * login
 * getErrorDescription
 * 
 * STATIC:
 * ------------------------------------------------------------------
 * model
 *
 */

class Accounts extends CActiveRecord
{

    /** @var string */    
    protected $_table = 'accounts';
    /** @var bool */
    private $_isError = false;    
    /** @var string */    
    private $_errorDescription = '';

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
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }  
    
    /**
     * Account login
     * @param string $username
     * @param string $password
     * @param string $role
     */
    public function login($username, $password, $role = '')
    {
        $this->_db->cacheOff();
        if($result = $this->find(
            ($role != '' ? "role = :role AND " : '').' username = :username AND password = :password',
            array(':username' => $username, ':password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password), ':role' => $role)            
        )){
            if($result->is_active){
                $session = A::app()->getSession();
                $session->set('loggedIn', true);
                $session->set('loggedId', $result->id);
                $session->set('loggedName', $result->username);
                $session->set('loggedEmail', $result->email);
                $session->set('loggedLastVisit', $result->last_visited_at);
                $session->set('loggedLanguage', ($result->language_code ? $result->language_code : Languages::getDefaultLanguage()));
                $session->set('loggedRole', $result->role);
                          
                // set current language
                if($resultLang = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$result->language_code))){
                    $params = array(
                        'locale' => $resultLang->lc_time_name,
                        'direction' => $resultLang->direction
                    );
                    A::app()->setLanguage($result->language_code, $params);
                }

                // update account's last visit time
                $this->_db->update(
                    $this->_table,
                    array(
                        'last_visited_at' => LocalTime::currentDateTime(),
                        'last_visited_ip' => A::app()->getRequest()->getUserHostAddress()
                    ),
                    'id = :id',
                    array(':id'=>(int)CAuth::getLoggedId())
                );
                return true;
            }else{
                if($result->registration_code != ''){
                    $this->_errorDescription = A::t('app', 'Login Not Approval Message');
                }else{
                    $this->_errorDescription = A::t('app', 'Login Inactive Message');
                }
            }
        }else{
            $this->_errorDescription = strip_tags(A::t('app', 'Login Error Message'));            
        }
        return false;        
    } 
    
	/** 
	 * Returns error description
	 * @return boolean
	 */
	public function getErrorDescription()
	{
		return $this->_errorDescription;
	}

}