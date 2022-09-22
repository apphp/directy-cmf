<?php
/**
 * EmailTemplates controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 *
 */

class EmailTemplatesController extends CController
{

    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to access email templates
		if(!Admins::hasPrivilege('email_templates', array('view', 'edit'))){
			$this->redirect('backend/index');
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Email Templates Management')));
        // Set backend mode
        Website::setBackend();
        
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

        // Prepare modules distinct names
		$result = EmailTemplates::model()->distinct('module_code');
        $modules = array('' => '');
        if(is_array($result)){
            foreach($result as $key => $val){
                $modules[$val['module_code']] = $val['module_code'];
            }
        }
		asort($modules);

        $this->_view->modules = $modules;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('emailTemplates/manage');    
    }

    /**
     * Manage email templates action handler
     */
	public function manageAction()
	{			
		// Block access if admin has no active privilege to manage email templates
        Website::prepareBackendAction('view', 'email_templates', 'backend/index');

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

		$this->_view->render('emailTemplates/manage');    
    }

    /**
     * Add new email template action handler
     */
	public function addAction()
	{		
		//bblock access if admin has no active privilege to add email templates
        Website::prepareBackendAction('edit', 'email_templates', 'emailTemplates/manage');
     	
		$this->_view->render('emailTemplates/add');        
	}
	
	/**
	 * Edit email template action handler
	 * @param int $id the template id 
	 */
	public function editAction($id = 0)
	{		
		// Block access if admin has no active privilege to edit email templates
        Website::prepareBackendAction('edit', 'email_templates', 'emailTemplates/manage');
		
     	$template = EmailTemplates::model()->findByPk((int)$id);
		if(!$template){
			$this->redirect('emailTemplates/manage');
		}
        
		$this->_view->template = $template;		
		$this->_view->render('emailTemplates/edit');        
	}
    
	/**
	 * Delete email template action handler
	 * @param int $id the template id 
	 */
	public function deleteAction($id = 0)
	{
		// Block access if admin has no active privilege to delete email templates
        Website::prepareBackendAction('edit', 'email_templates', 'emailTemplates/manage');
     	
     	$template = EmailTemplates::model()->findByPk((int)$id);
		if(!$template){
			$this->redirect('emailTemplates/manage');
		}

		$alert = '';
		$alertType = '';

		// Check if the template is system 
		if($template->is_system){
			$alert = A::t('app', 'Delete System Template Alert');
			$alertType = 'error';
		}elseif($template->delete()){				
			if($template->getError()){
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
				$alert = $template->getError() ? $template->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
        
		$this->redirect('emailTemplates/manage');
	}
    
}
