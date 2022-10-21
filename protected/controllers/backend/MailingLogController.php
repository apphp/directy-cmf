<?php
/**
 * MailingLog controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _checkActionAccess	
 * indexAction
 * manageAction
 * detailsAction
 * deleteAction
 * deleteAllAction
 * 
 */

class MailingLogController extends CController
{

	private $_backendPath = '';

	/**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if admin has no active privilege to access email templates
		if(!Admins::hasPrivilege('mailing_log', array('view'))){
			$this->redirect($this->_backendPath.'dashboard/index');
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Mailing Log')));
        // Set backend mode
        Website::setBackend();
        
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->backendPath = $this->_backendPath;

        // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->dateFormat = Bootstrap::init()->getSettings('date_format');
    }

	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect($this->_backendPath.'mailingLog/manage');
    }

    /**
     * Manage mailing log action handler
     */
	public function manageAction()
	{			
		// Block access if admin has no active privilege to manage mailing log
        Website::prepareBackendAction('view', 'mailing_log', 'dashboard/index');

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->logCount = MailingLog::model()->count();
		$this->_view->render($this->_backendPath.'mailingLog/manage');
    }
   
	/**
	 * View single email action handler
     * @param int $id the email ID
	 */
	public function detailsAction($id = 0)
	{
		// Block access if admin has no active privilege to manage mailing log
		Website::prepareBackendAction('view', 'mailing_log', 'dashboard/index');

		// Check log ID access
		$mailingLog = $this->_checkActionAccess($id);
		
		$this->_view->mail = $mailingLog;
		$this->_view->render($this->_backendPath.'mailingLog/details');
	}
   
    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
		// Block access if admin has no active privilege to delete mailing log
        Website::prepareBackendAction('delete', 'mailing_log', 'mailingLog/manage');

		// Check log ID access
        $mailingLog = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';
    
        if($mailingLog->delete()){
            if($mailingLog->getError()){
                $alert = A::t('app', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert = A::t('app', 'Delete Success Message');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
				$alert = $mailingLog->getError() ? $mailingLog->getErrorMessage() : A::t('app', 'Delete Error Message');
                $alertType = 'error';
            }
        }
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect($this->_backendPath.'mailingLog/manage');
    }
	
    /**
     * Delete all records action handler
     * @param int $id  
     */
    public function deleteAllAction()
    {
		// Block access if admin has no active privilege to delete mailing log
        Website::prepareBackendAction('delete', 'mailing_log', 'mailingLog/manage');

        $alert = '';
        $alertType = '';
		
		$mailingLog = MailingLog::model();
		
        if($mailingLog->deleteAll()){
            if($mailingLog->getError()){
                $alert = A::t('app', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert = A::t('app', 'Delete Success Message');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
				$alert = $mailingLog->getError() ? $mailingLog->getErrorMessage() : A::t('app', 'Delete Error Message');
                $alertType = 'error';
            }
        }

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect($this->_backendPath.'mailingLog/manage');
	}	

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $mailingLog = MailingLog::model()->findByPk($id);
        if(!$mailingLog){
            $this->redirect($this->_backendPath.'mailingLog/manage');
        }
        return $mailingLog;
    }    
  
}
