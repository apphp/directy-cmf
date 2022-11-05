<?php
/**
 * FaqCategories controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkCategoryAccess
 * indexAction              
 * manageAction             
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * 
 */

namespace Modules\Faq\Controllers;

// Modules
use \Modules\Faq\Components\FaqComponent,
	\Modules\Faq\Models\FaqCategories,
	\Modules\Faq\Models\FaqCategoryItems;

// Framework
use \A,
	\CAuth,
	\CController,
	\CDatabase,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\Languages,
	\ModulesSettings;

class FaqCategoriesController extends CController
{

	private $_backendPath = '';

    /**
	 * Class default constructor
     */
    public function __construct()
	{
        parent::__construct();
        
		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if the module is not installed
		if(!Modules::model()->isInstalled('faq')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
		}

        if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active language
            Website::setMetaTags(array('title'=>A::t('faq', 'FAQ Categories Management')));
			
			$this->_cSession = A::app()->getSession();

            $this->_view->tabs = FaqComponent::prepareTab('faq');

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->backendPath = $this->_backendPath;
        }
	}
    
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect('faqCategories/manage');	   	
    }	
    
    /**
     * Manage faq categories action handler
     */
    public function manageAction()
    {        
        Website::prepareBackendAction('manage', 'faq', 'faqCategories/manage', false);

        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->render('faqCategories/manage');
    }	
  
    /**
     * Add faq category action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'faq', 'faqCategories/manage', false);
        
        $this->_view->render('faqCategories/add');
    }   
    
    /**
     * Edit faq category action handler
     * @param int $id
     */
    public function editAction($id = 0)
    {         
        Website::prepareBackendAction('edit', 'faq', 'faqCategories/manage', false);
        $this->_view->faqCategory = $this->_checkCategoryAccess($id);
        
        $this->_view->render('faqCategories/edit');
    }  	
    
	/**
     * Change faq state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('edit', 'faq', 'faqCategories/manage', false);

        $faqCategory = $this->_checkCategoryAccess($id);
		$changeResult = FaqCategories::model()->updateByPk($id, array('is_active'=>($faqCategory->is_active == 1 ? '0' : '1')));

        if ($changeResult) {
            $alert = A::t('faq', 'FAQ Category status has been successfully changed!');
            $alertType = 'success';
        }else{
			$alert = APPHP_MODE == 'demo' ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('faq', 'An error occurred while changing FAQ Category status!');
			$alertType = APPHP_MODE == 'demo' ? 'warning' : 'error';
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
     
        $this->redirect('faqCategories/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Delete faq category action handler
     * @param int $id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'faq', 'faqCategories/manage', false);
        $faq = $this->_checkCategoryAccess($id);

        $alert = '';
        $alertType = '';     
    
        if($faq->delete()){                
            $alert = A::t('faq', 'FAQ category deleted successfully');
            $alertType = 'success'; 
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('faq', 'FAQ category deleting error');
                $alertType = 'error';
            }           
        }
		
        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
		
        $this->redirect('faqCategories/manage');
    }

    /**
     * Check if passed category ID is valid
     * @param int $catId
     */
    private function _checkCategoryAccess($catId = 0)
    {
        $faqCategory = FaqCategories::model()->findByPk((int)$catId);
        if(empty($faqCategory)){
            $this->redirect('faqCategories/manage');
        }
        return $faqCategory;        
    }      

}