<?php
/**
 * CDbHttpSession provides session-level data management by using database as session data storage.
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ---------- 
 * __construct                                          _startSession 
 * set
 * get
 * remove
 * isExists
 * setSessionName
 * getSessionName
 * getTimeout
 * endSession
 * 
 * openSession
 * closeSession
 * readSession
 * writeSession
 * destroySession
 * gcSession
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * init
 *
 */	  

class CDbHttpSession extends CComponent
{

	/** @var boolean */
	protected $_autoStart = true;
	/** @var string */
	protected $_defaultSessionName = 'apphp_framework';

    /** @var Database */
    private $_db;	
    
	
	/**
	 * Class default constructor
	 */
	function __construct()
	{
        $this->_db = CDatabase::init();
        
        @session_set_save_handler(
            array($this, 'openSession'),
            array($this, 'closeSession'),
            array($this, 'readSession'),
            array($this, 'writeSession'),
            array($this, 'destroySession'),
            array($this, 'gcSession')
        );
        
        $this->setSessionName('apphp_'.CConfig::get('installationKey'));		
		if($this->_autoStart) $this->_startSession();
	}

    /**
     *	Returns the instance of object
     *	@return CHttpSession class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}
    
	/**
	 * Sets session variable 
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	
	/**
	 * Returns session variable 
	 * @param string $name
	 * @param mixed $default
	 */
	public function get($name, $default = '')
	{
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
	}
    
	/**
	 * Removes session variable 
	 * @param string $name
	 */
	public function remove($name)
	{
		if(isset($_SESSION[$name])){
            unset($_SESSION[$name]);
            return true;
        }
        return false;
	}
    
	/**
	 * Checks if session variable exists
	 * @param string $name
	 */
	public function isExists($name)
	{
		return isset($_SESSION[$name]) ? true : false;
	}

	/**
	 * Sets session name
	 * @param string $value
	 */
	public function setSessionName($value)
	{
		if(empty($value)) $value = $this->_defaultSessionName;
		session_name($value);
	}

	/**
	 * Gets session name
	 * @return string 
	 */
	public function getSessionName()
	{
		return session_name();
	}

	/**
	 * Destroys the session
	 */
	public function endSession()
	{
		if(session_id() !== ''){
			@session_unset();
			@session_destroy();
		}
	}

  
	/**
	 * Session open handler
	 * Do not call this method directly
	 * @param string $savePath 
	 * @param string $sessionName 
	 * @return boolean 
	 */
	public function openSession($savePath, $sessionName)
	{
        $this->_db->delete('sessions', 'expires_at < '.time());
		return true;
	}
    
	/**
	 * Session close handler
	 * Do not call this method directly
	 * @return boolean 
	 */
	public function closeSession()
	{
		if(session_id() !== '') @session_write_close();
		return true;
	}

	/**
	 * Session read handler
	 * Do not call this method directly
	 * @param string $id 
	 * @return string 
	 */
	public function readSession($id)
	{
        $result = $this->_db->select("SELECT session_data FROM sessions WHERE session_id = '".$id."'");
        return isset($result[0]) ? $result[0] : '';
	}

	/**
	 * Session write handler
	 * Do not call this method directly
	 * @param string $id 
	 * @param string $data 
	 * @return boolean 
	 */
	public function writeSession($id, $data)
	{        
        $result = $this->_db->select("SELECT * from sessions WHERE session_id = '".$id."'");
        if(isset($result[0])){
            $result = $this->_db->update(
                'sessions',
                array(
                    'expires_at'=>time() + $this->getTimeout(),
                    'session_data'=>$data
                ),
                "session_id = '".$id."'"
            );
        }else{
            $result = $this->_db->insert(
                'sessions',
                array(
                    'session_id'=>$id,
                    'expires_at'=>time() + $this->getTimeout(),
                    'session_data'=>$data
                )
            );
        }
        
		return true;
	}

	/**
	 * Session destroy handler
	 * Do not call this method directly
	 * @param string $id 
	 * @return boolean 
	 */
	public function destroySession($id)
	{
		$this->_db->delete('sessions', "session_id = '".$id."'");        
		return true;
	}

	/**
	 * Session garbage collection handler
	 * Do not call this method directly
	 * @param int $maxLifetime 
	 * @return boolean 
	 */
	public function gcSession($maxLifetime)
	{
        $this->_db->delete('sessions', 'expires_at < '.time());		        
		return true;
	}
    
	/**
     * Returns the number of seconds after which data will be seen as 'garbage' and cleaned up
	 * @return integer 
	 */
	public function getTimeout()
	{
		return (int)ini_get('session.gc_maxlifetime');
	}

	/**
	 * Starts the session if it has not started yet
	 */
	private function _startSession()
	{
		@session_start();
		if(APPHP_MODE == 'debug' && session_id() == ''){
            Debug::addMessage('errors', 'session', A::t('core', 'Failed to start session'));
		}
	}

}