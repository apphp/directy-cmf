<?php
/**
* LanguagesController
*
* PUBLIC:                  	PRIVATE
* -----------              	------------------
* __construct              	getDirectonsList
* indexAction				getUsedOnList
* changeAction				
* manageAction				
* addAction
* editAction
* deleteAction
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
    	SiteSettings::setMetaTags(array('title'=>A::t('app', 'Languages')));
    	
    	$settings = Settings::model()->findByPk(1);
    	$this->view->frontEndTemplate = $settings->template; 

    	A::app()->view->setTemplate('backend');        
        $this->view->actionMessage = '';
        $this->view->errorField = '';
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
        if($result = Languages::model()->find('code = :code AND is_active = 1', array(':code'=>$lang))){
            $locale = isset($result[0]['lc_time_name']) ? $result[0]['lc_time_name'] : '';
			A::app()->setLanguage($lang, $locale);
        }
        $this->redirect('index/index');
    }

    /**
     * Manage languages action handler
     * @param string $msg 
     */
	public function manageAction($msg = '')
	{
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to view languages
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
			$this->view->actionMessage = CWidget::create('CMessage', array('success', $message, array('button'=>true)));
		}
		
    	$this->view->render('languages/manage');        
	}

	/**
	 * Add new language action handler
	 */
	public function addAction()
	{
        // block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to edit languages
		if(!Admins::hasPrivilege('languages', 'edit')){
			$this->redirect('backend/index');
		}
		
		$this->view->localesList = A::app()->getLocalTime()->getLocales();		
		$this->view->directionsList = $this->getDirectonsList();		
		$this->view->usedOnList = $this->getUsedOnList();
		
    	$this->view->render('languages/add');
	}

	/**
	 * Edit language action handler
     * @param int $id the language ID
     * @param string $icon has value 'delete' in order to delete the language icon file
	 */
	public function editAction($id = 0, $icon = '')
	{
		// block access to this controller for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to edit languages
		if(!Admins::hasPrivilege('languages', 'edit')){
			$this->redirect('backend/index');
		}
		
		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
		}
		
		$this->view->localesList = A::app()->getLocalTime()->getLocales();
		$this->view->directionsList = $this->getDirectonsList();
		$this->view->usedOnList = $this->getUsedOnList();	
		$this->view->language = $language;
	
		// delete the icon file
		if($icon == 'delete'){
			$msg = '';
			$errorType = '';
			$iconFile = 'images/flags/'.$language->icon;
			$language->icon = '';
			// save the changes in Languages table and delete the icon file
			if($language->save() && unlink($iconFile)){
				$msg = A::t('app', 'Image Delete Success Message');
				$errorType = 'success';
			}else{
				$msg = A::t('app', 'Image Delete Error Message');
				$this->view->errorField = '';
				$errorType = 'error';
			}
			if(!empty($msg)){
				$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
			}
		}
		
		$this->view->render('languages/edit');
	}

	/**
	 * Delete language action handler
	 * @param int $id The language id
	 */
	public function deleteAction($id = 0)
	{
		// block access to this action for not-logged users
		CAuth::handleLogin('backend/login');
		
		// block access if admin has no active privilege to edit languages
		if(!Admins::hasPrivilege('languages', 'edit')){
			$this->redirect('backend/index');
		}
		
		$msg = '';
		$errorType = '';
		
		// check if there is only one language
		if(Languages::model()->count() == 1){
			$msg = A::t('app', 'Delete Last Language Alert');
			$errorType = 'error';
			$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
			$this->view->render('languages/manage');
			return;
		}

		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
		}
		
		// check if the language is default
		if($language->is_default){
			$msg = A::t('app', 'Delete Default Alert');
			$errorType = 'error';
		}else if($language->delete()){				
			// delete messages folder for this language
			CFile::deleteDirectory('protected/messages/'.$language->code);	
			if($language->getError()){
				$msg = A::t('app', 'Delete Warning Message');
				$errorType = 'warning';
			}else{		
				$msg = A::t('app', 'Delete Success Message');
				$errorType = 'success';	
			}		
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$errorType = 'warning';
		   	}else{
				$msg = A::t('app', 'Delete Error Message');
				$errorType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->view->actionMessage = CWidget::create('CMessage', array($errorType, $msg, array('button'=>true)));
		}	
		$this->view->render('languages/manage');
	}

	/**
     *	Returns a list of language direction options
     *	@return array
	 */
	private function getDirectonsList()
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
	private function getUsedOnList()
	{
		return array(
			'' => A::t('app', '-- select --'),
			'global' => A::t('app', 'Global'),
			'front-end' => A::t('app', 'Front-End'),
			'back-end' => A::t('app', 'Back-End'),
		);
	}
	
}