<?php
/**
 * Dashboard controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              	
 * indexAction
 */

class DashboardController extends CController
{
	private $_checkBruteforce;
	private $_redirectDelay;
	private $_badLogins;
	private $_badRestores;
	private $_backendPath = '';

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access to this controller to non-logged visitors
		CAuth::handleLogin($this->_backendPath.'admin/login');

		// Set backend mode
        Website::setBackend();

		$this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->alerts = '';
		$this->_view->backendPath = $this->_backendPath;

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();
    }
        
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $alerts = array();
        // Draw predefined alerts
        if(APPHP_MODE == 'debug') $alerts[] = array('type'=>'warning', 'message'=>A::t('app', 'Debug Mode Alert'));
		if(CConfig::get('cookies.path') == '/' && $this->_cRequest->getBasePath() != '/'){
			$alerts[] = array('type' => 'warning', 'message' => A::t('app', 'Cookies Base Path Alert', array('{path}' => $this->_cRequest->getBasePath())));
		}
        if(CAuth::getLoggedEmail() == '' || preg_match('/example.com/i', CAuth::getLoggedEmail())){
			$alerts[] = array('type' => 'error', 'message' => A::t('app', 'Default Email Alert', array('{SITE_BO_URL}' => Website::getBackendPath())));
		}
		if(in_array(CAuth::getLoggedRole(), array('owner', 'mainadmin'))){
			if(($errorLogSize = CFile::getFileSize('protected/tmp/logs/error.log')) > 0){
				$alerts[] = array('type'=>'error', 'message'=>A::t('core', 'There seems to be some errors in your system: error log file is not empty ({size}Kb)! Click <a href="{SITE_BO_URL}error/viewErrorLog">here</a> to check it.', array('{size}'=>$errorLogSize, '{SITE_BO_URL}'=>Website::getBackendPath())));
			}
		}
		
		// Draw alerts from modules		
		$modules = Modules::model()->findAll('is_active = 1');		
		if(is_array($modules)){
			foreach($modules as $key => $val){
                $class = $val['code'].'Component';
                if(CClass::isExists($class) && CClass::isMethodExists($class, 'drawAlerts')){
                    if($alert = call_user_func_array($class.'::drawAlerts', array())){
                        $alerts[] = array('type'=>'warning', 'message'=>$alert);
                    }
                }
			}
		}
        $this->_view->alerts = $alerts;
        
        // Fetch modules that need to be shown in hotkeys
        $this->_view->modulesToShow = Modules::model()->findAll('show_on_dashboard=1 AND is_active=1');

		// Fetch datetime format from settings table
    	$dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		
        $this->_view->dashboardHotkeys = Bootstrap::init()->getSettings('dashboard_hotkeys');
        $this->_view->dashboardNotifications = Bootstrap::init()->getSettings('dashboard_notifications');
        $this->_view->dashboardStatistics = Bootstrap::init()->getSettings('dashboard_statistics');
        $this->_view->todayDate = CLocale::date($dateTimeFormat);
        $this->_view->lastLoginDate = strtotime(CAuth::getLoggedLastVisit()) > 0 ? CLocale::date($dateTimeFormat, CAuth::getLoggedLastVisit()) : A::t('app', 'Never');
        $this->_view->adminsCount = Admins::model()->count();
		$this->_view->scriptName = CConfig::get('name');
		$this->_view->scriptVersion = CConfig::get('version');
		$this->_view->dateTimeFormat = $dateTimeFormat;

		// Fetch last 5 admins
		$lastAdmins = Admins::model()->findAll(array('condition'=>"role != 'owner'", 'order'=>'id DESC', 'limit'=>'5'));
		$lastAdminsList = array();
		if(is_array($lastAdmins)){
			foreach($lastAdmins as $admin){
				$lastAdminsList[] = $admin['username'];	
			}
		}
		$this->_view->lastAdminsList = $lastAdminsList;
		
		// Fetch admins who changed password
		$changedPasswordAdmins = Admins::model()->findAll(array('condition'=>"role != 'owner' AND password_changed_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)", 'order'=>'id DESC', 'limit'=>'5'));
		$changedPasswordAdminsList = array();
		if(is_array($changedPasswordAdmins)){
			foreach($changedPasswordAdmins as $admin){
				$changedPasswordAdminsList[] = $admin['username'];	
			}
		}
		$this->_view->changedPasswordAdminsList = $changedPasswordAdminsList;
		
		// Prepare session's data
		$this->_view->customStorage = CConfig::get('session.customStorage');
		$sessions = CDatabase::init()->select('SELECT COUNT(*) cnt FROM '.CConfig::get('db.prefix').'sessions');
		$this->_view->activeSessions = isset($sessions[0]['cnt']) ? $sessions[0]['cnt'] : 0;

		// Prepare notifications 
		$systemNotifications = array();		
		$condition = '';
		$loggedRole = CAuth::getLoggedRole();
		if($loggedRole == 'owner'){
			$condition = '';
		}elseif($loggedRole == 'mainadmin'){
			$condition = "minimal_role != 'owner'";
		}elseif($loggedRole == 'admin'){
			$condition = "minimal_role NOT IN ('owner', 'mainadmin')";
		}elseif(!empty($loggedRole)){
			$condition = "(minimal_role = '".$loggedRole."')";
		}		
		
        if($notifications = SystemNotifications::model()->findAll(array('condition'=>$condition, 'order'=>'id DESC', 'limit'=>'0, 10'))){
			foreach($notifications as $key => $val){
				$systemNotifications[$key] = array(
					'title' => $val['title'],
					'content' => $val['content'],
					'type' => $val['type'],
					'module_code' => $val['module_code'],
					'date' => $val['created_at'],
				);
			}
		}
		$this->_view->systemNotifications = $systemNotifications;

        $this->_view->render($this->_backendPath.'dashboard/index');
    }

}