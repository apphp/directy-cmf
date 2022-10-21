<?php
/**
 * BanLists controller
 *
 * PUBLIC:                  PRIVATE
 * ---------------          ---------------
 * __construct              _checkActionAccess
 * indexAction              
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 *
 */

class BanListsController extends CController
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

		// Block access if admin has no active privilege to access ban lists
		if(!Admins::hasPrivilege('ban_lists', array('view', 'edit'))){
			$this->redirect($this->_backendPath.'dashboard/index');
		}

        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Ban Lists Management')));
        // Set backend mode
        Website::setBackend();

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->backendPath = $this->_backendPath;
		$this->_view->itemTypes = array('ip_address'=>A::t('app', 'IP Address'), 'email_address'=>A::t('app', 'Email Address'), 'email_domain'=>A::t('app', 'Email Domain'), 'username'=>A::t('app', 'Username'));

        // Fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->dateFormat = Bootstrap::init()->getSettings('date_format');
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect($this->_backendPath.'banLists/manage');
    }
    
    /**
     * Manage action handler
     * @param string $msg 
     */
    public function manageAction($msg = '')
    {
		// Block access if admin has no active privilege to manage ban lists
        Website::prepareBackendAction('view', 'ban_lists', 'dashboard/index');

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_view->render($this->_backendPath.'banLists/manage');
    }
	
    /**
     * Add new action handler
     */
    public function addAction()
    {
		// Block access if admin has no active privilege to add ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');

		$this->_view->itemType = A::app()->getRequest()->getPost('item_type');
        $this->_view->render($this->_backendPath.'banLists/add');
    }

    /**
     * Edit banlists action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
		// Block access if admin has no active privilege to edit ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banList = $this->_checkActionAccess($id);
        
        $this->_view->id = $banList->id;
		$itemType = A::app()->getRequest()->getPost('item_type');
		$this->_view->itemType = !empty($itemType) ? $itemType : $banList->item_type;	
        $this->_view->render($this->_backendPath.'banLists/edit');
    }

    /**
     * Change status action handler
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 0)
    {
		// Block access if admin has no active privilege to change ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banList = $this->_checkActionAccess($id);

        if(BanLists::model()->updateByPk($id, array('is_active'=>($banList->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
        }else{
            $alert = ((APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error'));
			$alertType = 'error';
        }
        
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect($this->_backendPath.'banLists/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
		// Block access if admin has no active privilege to delete ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banList = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';
    
        // Check if default
        if($banList->is_default){
            $alert = A::t('app', 'Delete Default Alert');
            $alertType = 'error';
        }elseif($banList->delete()){
            if($banList->getError()){
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
				$alert = $banList->getError() ? $banList->getErrorMessage() : A::t('app', 'Delete Error Message');
                $alertType = 'error';
            }
        }
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect($this->_backendPath.'banLists/manage');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $banList = BanLists::model()->findByPk($id);
        if(!$banList){
            $this->redirect($this->_backendPath.'banLists/manage');
        }

        return $banList;
    }
  
}