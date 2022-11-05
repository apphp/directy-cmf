<?php
/**
 * Banners controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------				
 * __construct              _checkActionAccess
 * indexAction              
 * manageAction
 * changeStatusAction
 * addAction
 * editAction
 * deleteAction
 * viewAllAction
 *
 */

namespace Modules\Banners\Controllers;

// Modules
use \Modules\Banners\Components\BannersComponent,
	\Modules\Banners\Models\Banners;

// Framework
use \A,
	\CAuth,
	\CController,
	\CDatabase,
	\CFile,
	\CWidget;

// Directy
use \Modules,
	\Website;



class BannersController extends CController
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
		if(!Modules::model()->isInstalled('banners')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
		}

        if(CAuth::isLoggedIn()){
            // Set meta tags according to active BannersController
            Website::setMetaTags(array('title'=>A::t('banners', 'Banners Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            
            $this->_view->tabs = BannersComponent::prepareTab('banners');
			$this->_view->backendPath = $this->_backendPath;
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('banners/manage');
    }
    
    /**
     * Manage action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'banners', 'banners/manage', false);

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        
        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
		
        $this->_view->render('banners/manage');
    }

	/**
     * Change banner state method
	 * @param int $id 		the banner ID
	 * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('edit', 'banners', 'banners/manage', false);

        $banner = $this->_checkActionAccess($id);

		if(!empty($banner)){
            if(Banners::model()->updateByPk($id, array('is_active'=>($banner->is_active == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('banners', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
				A::app()->getSession()->setFlash('alert', (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', (APPHP_MODE == 'demo') ? 'warning' : 'error');
            }
        }
     
        $this->redirect('banners/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'banners', 'banners/manage', false);
        $this->_view->render('banners/add');
    }

    /**
     * Edit Banners edit handler
     * @param int $id
	 * @param string $image
     */
    public function editAction($id = 0, $image = '')
    {
        Website::prepareBackendAction('edit', 'banners', 'banners/manage/id/'.$id, false);
        $banners = $this->_checkActionAccess($id);
		
        // Delete the image file
        if($image === 'delete'){
        	$alert = '';
        	$alertType = '';
        	$image = 'assets/modules/banners/images/items/'.$banners->image_file;
            $imageThumb = 'assets/modules/banners/images/items/'.$banners->image_file_thumb;
        	$banners->image_file = '';
            $banners->image_file_thumb = '';

        	// Save the changes in banners table
        	if($banners->save()){
        		// Delete images
        		if(CFile::deleteFile($image) && CFile::deleteFile($imageThumb)){
        			$alert = A::t('banners', 'Banner successfully deleted');
        			$alertType = 'success';
        		}else{
        			$alert = A::t('banners', 'There was a problem removing the banner');
        			$alertType = 'warning';
        		}
        	}else{
        		$alert = A::t('banners', 'Error removing banner');
        		$alertType = 'error';
        	}
        	if(!empty($alert)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        	}
        }
        $this->_view->banners = $banners;
        $this->_view->render('banners/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'banners', 'banners/manage', false);
        $model = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';
		$image = 'assets/modules/banners/images/items/'.$model->image_file;
		$imageThumb = 'assets/modules/banners/images/items/'.$model->image_file_thumb;
    
        // Check if default
        if($model->is_default){
            $alert = A::t('app', 'Delete Default Alert');
            $alertType = 'error';
        }elseif($model->delete()){
            if($model->getError()){
                $alert = A::t('app', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
        		// Delete images
        		if(CFile::deleteFile($image) && CFile::deleteFile($imageThumb)){
        			$alert = A::t('banners', 'Banner successfully deleted');
        			$alertType = 'success';
        		}else{
        			$alert = A::t('banners', 'There was a problem removing the banner');
        			$alertType = 'warning';
        		}
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('app', 'Delete Error Message');
                $alertType = 'error';
            }
        }
        
		if(!empty($alert)){
			A::app()->getSession()->setFlash('alert', $alert);
			A::app()->getSession()->setFlash('alertType', $alertType);
        }
		
        $this->redirect('banners/manage');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = Banners::model()->findByPk($id);
        if(!$model){
            $this->redirect('banners/manage');
        }
        return $model;
    }    
 
}