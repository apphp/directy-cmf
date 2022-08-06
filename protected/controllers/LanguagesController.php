<?php
/**
 * Languages controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _getDirectonsList
 * indexAction				_getUsedOnList
 * changeAction				
 * manageAction				
 * addAction
 * editAction
 * deleteAction
 * 
 */

class LanguagesController extends CController
{    
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Languages')));
        // set backend mode
        Website::setBackend();
    	
    	$this->_view->frontEndTemplate = Bootstrap::init()->getSettings('template'); 
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }
	
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        $this->redirect('languages/manage');        
	}
    
    /**
     * Changes language on site
	 * @param string $lang code of the new site language
     */
    public function changeAction($lang)
    {
        // if redirected from dropdown list
        if(empty($lang)) $lang = A::app()->getRequest()->getQuery('lang');
        
        // check for existing $lang in DB
        if($result = Languages::model()->find("code = :code AND used_on IN ('front-end','global') AND is_active = 1", array(':code'=>$lang))){
            $params = array(
                'locale' => $result->lc_time_name,
                'direction' => $result->direction
            );
            A::app()->setLanguage($lang, $params);
        }
		
        $this->redirect(Website::getDefaultPage());
    }

    /**
     * Manage languages action handler
     * @param string $msg 
     */
	public function manageAction($msg = '')
	{
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to manage languages
        if(!Admins::hasPrivilege('languages', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
		switch($msg){
			case 'added':
				$message = A::t('core', 'The adding operation has been successfully completed!');
				break;
			case 'updated':
				$message = A::t('core', 'The updating operation has been successfully completed!');
				break;
			default:
				$message = '';
		}
		if(!empty($message)){
			$this->_view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
		}
		
    	$this->_view->render('languages/manage');        
	}

	/**
	 * Add new language action handler
	 */
	public function addAction()
	{
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to add languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$this->_view->localesList = A::app()->getLocalTime()->getLocales();		
		$this->_view->directionsList = $this->_getDirectonsList();		
		$this->_view->usedOnList = $this->_getUsedOnList();
		
        $sortOrder = Languages::model()->count();
        $this->_view->sortOrder = ($sortOrder < 99) ? $sortOrder + 1 : 99;        		        
    	$this->_view->render('languages/add');
	}

	/**
	 * Edit language action handler
     * @param int $id the language ID
     * @param string $icon has value 'delete' in order to delete the language icon file
	 */
	public function editAction($id = 0, $icon = '')
	{
		// block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to edit languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
		}
		
		$this->_view->localesList = A::app()->getLocalTime()->getLocales();
		$this->_view->directionsList = $this->_getDirectonsList();
		$this->_view->usedOnList = $this->_getUsedOnList();	
		$this->_view->language = $language;
	
		// delete the icon file
		if($icon == 'delete'){
			$msg = '';
			$msgType = '';
			$iconFile = 'images/flags/'.$language->icon;
			$language->icon = '';
			// save the changes in Languages table and delete the icon file
			if($language->save() && CFile::deleteFile($iconFile)){
				$msg = A::t('app', 'Image Delete Success Message');
				$msgType = 'success';
			}else{
				$msg = A::t('app', 'Image Delete Error Message');
				$msgType = 'error';
				$this->_view->errorField = '';
			}
			if(!empty($msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
			}
		}
		$this->_view->render('languages/edit');
	}

	/**
	 * Delete language action handler
	 * @param int $id The language id
	 */
	public function deleteAction($id = 0)
	{
		// block access to this action to non-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to delete languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$msg = '';
		$msgType = '';
		
		// check if there is only one language
		if(Languages::model()->count() == 1){
			$msg = A::t('app', 'Delete Last Language Alert');
			$msgType = 'error';
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
			$this->_view->render('languages/manage');
			return;
		}

		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
		}
		
		// check if the language is default
		if($language->is_default){
			$msg = A::t('app', 'Delete Default Alert');
			$msgType = 'error';
		}else if($language->delete()){				
			// delete messages folder for this language
			CFile::deleteDirectory('protected/messages/'.$language->code);	
			if($language->getError()){
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
				$msg = $language->getError() ? $language->getErrorMessage() : A::t('app', 'Delete Error Message');
				$msgType = 'error';
		   	}			
		}
		
		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}		
		
		// block access if admin has no active privilege to view currencies		
		if(Admins::hasPrivilege('languages', array('view'))){
			$this->_view->render('languages/manage');
		}else{
			$this->redirect('languages/manage');
		}		
	}

	/**
     *	Returns a list of language direction options
     *	@return array
	 */
	private function _getDirectonsList()
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