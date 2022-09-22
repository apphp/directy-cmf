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

        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Languages')));
        // Set backend mode
        Website::setBackend();
    	
		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

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
    public function changeAction($lang = '')
    {
        // If redirected from dropdown list
        if(empty($lang)) $lang = A::app()->getRequest()->getQuery('lang');
        
        // Check for existing $lang in DB
        if($result = Languages::model()->find("code = :code AND used_on IN ('front-end','global') AND is_active = 1", array(':code'=>$lang))){
            $params = array(
				'name' => $result->name,
				'name_native' => $result->name_native,
                'locale' => $result->lc_time_name,
                'direction' => $result->direction,
				'icon' => $result->icon,
            );
            A::app()->setLanguage($lang, $params);
        }
		
		$referrerPage = Website::getRefererPage();
		$defaultPage = Website::getDefaultPage();
		$baseUrl = A::app()->getRequest()->getBaseUrl();
		
		// If referrer page exists and it comes from current domain redirect to referrer URL, otherwise to default page		
		if(!empty($referrerPage) && preg_match('/'.preg_quote($baseUrl, '/').'/', $referrerPage)){
			$this->redirect($referrerPage, true);
		}else{
			$this->redirect($defaultPage);
		}
    }

    /**
     * Manage languages action handler
     */
	public function manageAction()
	{
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to manage languages
        if(!Admins::hasPrivilege('languages', array('view', 'edit'))){
        	$this->redirect('backend/index');
        }
		
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

    	$this->_view->render('languages/manage');        
	}

	/**
	 * Add new language action handler
	 */
	public function addAction()
	{
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to add languages
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
		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to edit languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
		}
		
		$this->_view->localesList = A::app()->getLocalTime()->getLocales();
		$this->_view->directionsList = $this->_getDirectonsList();
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
		$this->_view->render('languages/edit');
	}

	/**
	 * Delete language action handler
	 * @param int $id The language id
	 */
	public function deleteAction($id = 0)
	{
		// Block access to this action to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());
		
		// Block access if admin has no active privilege to delete languages
        Website::prepareBackendAction('edit', 'languages', 'languages/manage');
		
		$alert = '';
		$alertType = '';
		
		// Check if there is only one language
		if(Languages::model()->count() == 1){
			$alert = A::t('app', 'Delete Last Language Alert');
			$alertType = 'error';
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
			$this->_view->render('languages/manage');
			return;
		}

		$language = Languages::model()->findByPk($id);
		if(!$language){
			$this->redirect('languages/manage');
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
		
		$this->redirect('languages/manage');
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