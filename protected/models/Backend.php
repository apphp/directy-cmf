<?php
/**
 * Backend model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * login
 *
 * STATIC:
 * ------------------------------------------
 *
 */
class Backend extends CModel
{

    /** @var string */    
    protected $_table = 'admins';

    /**
	 * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Admin login
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {        
        $result = $this->db->select('
            SELECT *
            FROM '.CConfig::get('db.prefix').$this->_table.'
            WHERE username = :username AND password = :password AND is_active = 1',
            array(
                ':username' => $username,
                ':password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password)
            )
        );
        
        if(!empty($result)){
            $session = A::app()->getSession();
            $session->set('loggedIn', true);
            $session->set('loggedId', $result[0]['id']);
            $session->set('loggedName', (!empty($result[0]['display_name']) ? $result[0]['display_name'] : $result[0]['username']));
            $session->set('loggedAvatar', (!empty($result[0]['avatar']) ? $result[0]['avatar'] : 'no_image.png'));
            $session->set('loggedEmail', (!empty($result[0]['email']) ? $result[0]['email'] : $result[0]['email']));
			$session->set('loggedLastVisit', (!empty($result[0]['lastvisited_at']) ? $result[0]['lastvisited_at'] : $result[0]['lastvisited_at']));
            $session->set('loggedRole', $result[0]['role']);

	        // set current language
	    	A::app()->setLanguage($result[0]['language_code']);

	    	// update admin's last visit time
			$this->db->update($this->_table, array('lastvisited_at' => date('Y-m-d H:i:s')), 'id = '.(int)CAuth::getLoggedId());
	    	return true;
        }else{
            
            return false;        
        }        
    }       
 
}
