<?php
/**
 * BanLists controller
 *
 * PUBLIC:                  PRIVATE
 * ---------------          ---------------
 * __construct              _checkActionAccess
 * indexAction              
 * manageAction
 * changeStatusAction
 * addAction
 * editAction
 * deleteAction
 *
 */

class BanListsController extends CController
{
	
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to access ban lists
		if(!Admins::hasPrivilege('ban_lists', array('view', 'edit'))){
			$this->redirect('backend/index');
		}

        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Ban Lists Management')));
        // set backend mode
        Website::setBackend();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
		$this->_view->item_types = array(''=>'', 'ip'=>A::t('app', 'IP Address'), 'email'=>A::t('app', 'Email'), 'username'=>A::t('app', 'Username'));

        // fetch datetime format from settings table
        $this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->dateFormat = Bootstrap::init()->getSettings('date_format');
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('banLists/manage');
    }
    
    /**
     * Manage action handler
     * @param string $msg 
     */
    public function manageAction($msg = '')
    {
		// block access if admin has no active privilege to manage ban lists
        Website::prepareBackendAction('view', 'ban_lists', 'backend/index');

        if(A::app()->getSession()->hasFlash('message')){
            $msg = A::app()->getSession()->getFlash('message');
        }

        switch($msg){
            case 'added':
                $message = A::t('app', 'Adding operation has been successfully completed!');
                break;
            case 'updated':
                $message = A::t('app', 'Updating operation has been successfully completed!');
                break;
            case 'changed':
                $message = A::t('app', 'Status has been successfully changed!');
                break;
            case 'change-error':
				$message = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
				$messageType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
                break;
            default:
                $message = '';
				break;
        }
		
        if(!empty($message)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array('success', $message, array('button'=>true))
            );
        }

        $this->_view->render('banLists/manage');        
    }
	
    /**
     * Change status action handler
     * @param int $id
     */
    public function changeStatusAction($id)
    {
		// block access if admin has no active privilege to change ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banLists = $this->_checkActionAccess($id);

        if(BanLists::model()->updateByPk($id, array('is_active'=>($banLists->is_active == 1 ? '0' : '1')))){
            A::app()->getSession()->setFlash('message', 'changed');
        }else{
            A::app()->getSession()->setFlash('message', 'change-error');
        }
        
        $this->redirect('banLists/manage');        
    }
	

    /**
     * Add new action handler
     */
    public function addAction()
    {
		// block access if admin has no active privilege to add ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');

		$this->_view->itemType = A::app()->getRequest()->getPost('item_type');
        $this->_view->render('banLists/add');
    }

    /**
     * Edit banlists action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
		// block access if admin has no active privilege to edit ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banLists = $this->_checkActionAccess($id);
        
        $this->_view->id = $banLists->id;
		$this->_view->itemType = A::app()->getRequest()->getPost('item_type');
        $this->_view->render('banLists/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
		// block access if admin has no active privilege to delete ban lists
        Website::prepareBackendAction('edit', 'ban_lists', 'banLists/manage');
        $banLists = $this->_checkActionAccess($id);

        $msg = '';
        $msgType = '';
    
        // check if default
        if($banLists->is_default){
            $msg = A::t('app', 'Delete Default Alert');
            $msgType = 'error';
        }else if($banLists->delete()){
            if($banLists->getError()){
                $msg = A::t('app', 'Delete Warning Message');
                $msgType = 'warning';
            }else{
                $msg = A::t('app', 'Delete Success Message');
                $msgType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $msg = CDatabase::init()->getErrorMessage();
                $msgType = 'warning';
            }else{
				$msg = $banLists->getError() ? $banLists->getErrorMessage() : A::t('app', 'Delete Error Message');
                $msgType = 'error';
            }
        }
		
        if(!empty($msg)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($msgType, $msg, array('button'=>true))
            );
        }

		// block access if admin has no active privilege to view ban lists
		if(Admins::hasPrivilege('ban_lists', array('view'))){
			$this->_view->render('banLists/manage');
		}else{
			$this->redirect('banLists/manage');
		}
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $banLists = BanLists::model()->findByPk($id);
        if(!$banLists){
            $this->redirect('banLists/manage');
        }
        return $banLists;
    }    
  
}