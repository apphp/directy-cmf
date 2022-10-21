<?php
/**
 * Languages controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _getDirectionsList
 * indexAction				_getUsedOnList
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * 
 */

class LanguagesController extends CController
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

        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Languages')));
        // Set backend mode
        Website::setBackend();
    	
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		$this->_view->backendPath = $this->_backendPath;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
	
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect($this->_backendPath.'languages/manage');
	}
    
    /**
     * Manage languages action handler
     */
	public function manageAction()
	{
		// Block access if admin has no active privilege to manage languages
        if(!Admins::hasPrivilege('languages', array('view', 'edit'))){
        	$this->redirect($this->_backendPath.'dashboard/index');
        }
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

    	$this->_view->render($this->_backendPath.'languages/manage');
	}

	/**
	 * Add new language action handler
	 */
	public function addAction()
	{
		// Block access if admin has no active privilege to add languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$this->_view->localesList = A::app()->getLocalTime()->getLocales();		
		$this->_view->directionsList = $this->_getDirectionsList();
		$this->_view->usedOnList = $this->_getUsedOnList();
		
        $sortOrder = Languages::model()->count();
        $this->_view->sortOrder = ($sortOrder < 99) ? $sortOrder + 1 : 99;        		        
    	$this->_view->render($this->_backendPath.'languages/add');
	}

	/**
	 * Edit language action handler
     * @param int $id the language ID
     * @param string $icon has value 'delete' in order to delete the language icon file
	 */
	public function editAction($id = 0, $icon = '')
	{
		// Block access if admin has no active privilege to edit languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect($this->_backendPath.'languages/manage');
		}
		
		$this->_view->localesList = A::app()->getLocalTime()->getLocales();
		$this->_view->directionsList = $this->_getDirectionsList();
		$this->_view->usedOnList = $this->_getUsedOnList();	
		$this->_view->language = $language;
	
		// Delete the icon file
		if($icon == 'delete'){
			$alert = '';
			$alertType = '';
			$iconFile = 'images/flags/'.$language->icon;
			$language->icon = '';
			// Save the changes in Languages table and delete the icon file
			if($language->save() && CFile::deleteFile($iconFile)){
				$alert = A::t('app', 'Image Delete Success Message');
				$alertType = 'success';
			}else{
				$alert = A::t('app', 'Image Delete Error Message');
				$alertType = 'error';
				$this->_view->errorField = '';
			}
			if(!empty($alert)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
			}
		}
		$this->_view->render($this->_backendPath.'languages/edit');
	}

    /**
     * Change status languages action handler
     * @param int $id the language ID
     */
    public function changeStatusAction($id)
    {
		// Block access if admin has no active privilege to edit languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect($this->_backendPath.'languages/manage');
		}
		
		// Check if the language is default
		if($language->is_default){
			$alert = A::t('app', 'Change Status Default Alert');
			$alertType = 'error';
		}elseif(Languages::model()->updateByPk($id, array('is_active'=>($language->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
		}else{
			$alert = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
		}
		 
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'languages/manage');
    }
	
	/**
	 * Delete language action handler
	 * @param int $id The language id
	 */
	public function deleteAction($id = 0)
	{
		// Block access if admin has no active privilege to delete languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$alert = '';
		$alertType = '';
		
		// Check if there is only one language
		if(Languages::model()->count() == 1){
			$alert = A::t('app', 'Delete Last Language Alert');
			$alertType = 'error';
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
			$this->_view->render($this->_backendPath.'languages/manage');
			return;
		}

		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect($this->_backendPath.'languages/manage');
		}
		
		// Check if the language is default
		if($language->is_default){
			$alert = A::t('app', 'Delete Default Alert');
			$alertType = 'error';
		}elseif($language->delete()){				
			// Delete messages folder for this language
			CFile::deleteDirectory('protected/messages/'.$language->code);	
			if($language->getError()){
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
				$alert = $language->getError() ? $language->getErrorMessage() : A::t('app', 'Delete Error Message');
				$alertType = 'error';
		   	}			
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect($this->_backendPath.'languages/manage');
	}

	/**
     *	Returns a list of language direction options
     *	@return array
	 */
	private function _getDirectionsList()
	{
		return array(
			'' => A::t('app', '-- select --'),
			'ltr' => A::t('app', 'LTR (left-to-right)'),
			'rtl' => A::t('app', 'RTL (right-to-left)'),
		);
	}
	
	/**
     *	Returns a list of language used-on options
     *	@return array
	 */
	private function _getUsedOnList()
	{
		return array(
			'' => A::t('app', '-- select --'),
			'global' => A::t('app', 'Global'),
			'front-end' => A::t('app', 'Front-End'),
			'back-end' => A::t('app', 'Back-End'),
		);
	}
	
}