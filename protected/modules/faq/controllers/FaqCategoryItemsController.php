<?php
/**
 * FaqCategoryItems controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkCategoryAccess
 * indexAction              _checkCategoryItemAccess
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

class FaqCategoryItemsController extends CController
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

        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('faq', 'FAQ Category Items Management')));
        
		$this->_cSession = A::app()->getSession();
		
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
        $this->_view->backendPath = $this->_backendPath;
    }
    
    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('faqCategoryItems/manage');        
    }  
    
    /**
     * Manage faq category items action handler
     * @param int $catId 
     */
    public function manageAction($catId = '')
    {         
        Website::prepareBackendAction('manage', 'faq', 'faqCategoryItems/manage', false);
        $this->_checkCategoryAccess($catId);       
       
        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }
		
        $this->_view->render('faqCategoryItems/manage');      
    }
    
    /**
     * Add faq category item action handler
     * @param int $catId 
     */    
    public function addAction($catId = 0)
    {
        Website::prepareBackendAction('add', 'faq', 'faqCategoryItems/manage', false);
        $this->_checkCategoryAccess($catId);
        
        $this->_view->render('faqCategoryItems/add');
    }
    
    /**
     * Edit faq category item action handler
     * @param int $catId
     * @param int $id
     */
    public function editAction($catId = 0, $id= 0)
    {
        Website::prepareBackendAction('edit', 'faq', 'faqCategoryItems/manage', false);
        $this->_checkCategoryAccess($catId);

        $this->_view->faqCategoryItem = $this->_checkCategoryItemAccess($id);
        $this->_view->render('faqCategoryItems/edit');
    }    
    
	/**
     * Change faq item state method
     * @param int $catId
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($catId = 0, $id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'faq', 'faqCategoryItems/manage', false);
		$this->_checkCategoryAccess($catId);
        $faqCategoryItem = $this->_checkCategoryItemAccess($id);
        
		$changeResult = FaqCategoryItems::model()->updateByPk($id, array('is_active'=>($faqCategoryItem->is_active == 1 ? '0' : '1')));

        if ($changeResult) {
            $alert = A::t('faq', 'FAQ Category Item status has been successfully changed!');
            $alertType = 'success';
        }else{
			$alert = APPHP_MODE == 'demo' ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('faq', 'An error occurred while changing FAQ Category Item status!');
			$alertType = APPHP_MODE == 'demo' ? 'warning' : 'error';
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
     
        $this->redirect('faqCategoryItems/manage/catId/'.$catId.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Delete faq category item action handler
     * @param int $catId
     * @param int $id
     */    
    public function deleteAction($catId = 0, $id= 0)
    {
        Website::prepareBackendAction('delete', 'faq', 'faqCategoryItems/manage', false);
        $this->_checkCategoryAccess($catId);
        $faq = $this->_checkCategoryItemAccess($id);        
       
        $alert = '';
        $alertType = '';
        
        if($faq->delete()){                        
            $alert = A::t('faq', 'FAQ category item deleted successfully');
            $alertType = 'success'; 
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('faq', 'FAQ category item deleting error');
                $alertType = 'error';
            }           
        }
		
        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
        
		$this->redirect('faqCategoryItems/manage/catId/'.$catId);
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
         
        $this->_view->catId = $catId;
        $this->_view->categoryName = $faqCategory->category_name;
        $this->_view->categoryLink = 'faqCategoryItems/manage/catId/'.$catId;
        $this->_view->tabs = FaqComponent::prepareTab('faqitems', $catId);
       
        return $faqCategory;
    }
    
    /**
     * Check if passed category item ID is valid
     * @param int $id
     */
    private function _checkCategoryItemAccess($id = 0)
    {
        $faqCategoryItem = FaqCategoryItems::model()->findByPk($id);
        if(!$faqCategoryItem){
            $this->redirect('faqCategoryItems/manage/catId/'.$catId);
        }
        
        return $faqCategoryItem;
    }

}