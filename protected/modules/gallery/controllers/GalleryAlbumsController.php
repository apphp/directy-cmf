<?php
/**
 * GalleryAlbums controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------         		------------------
 * __construct              _checkAlbumAccess
 * indexAction              
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * 
 */

namespace Modules\Gallery\Controllers;

// Modules
use \Modules\Gallery\Components\GalleryComponent,
	\Modules\Gallery\Models\GalleryAlbums,
	\Modules\Gallery\Models\GalleryAlbumItems;

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

class GalleryAlbumsController extends CController
{
	
	private $_settings;
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
        if(!Modules::model()->isInstalled('gallery')){	
        	if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
        	}else{
        		$this->redirect(Website::getDefaultPage());
        	}
        }

        if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active language
            Website::setMetaTags(array('title'=>A::t('gallery', 'Gallery Albums Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
			$this->_view->backendPath = $this->_backendPath;
            $this->_view->albumTypes = array(
                '0'=>A::t('gallery', 'Images')                
            );
            //'1'=>array('optionValue'=>A::t('gallery', 'Video'), 'optionDisabled'=>true)
			
			$this->_cRequest = A::app()->getRequest();
			$this->_cSession = A::app()->getSession();
			
            $this->_view->tabs = GalleryComponent::prepareTab('gallery');		
        }
    }
    
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect('galleryAlbums/manage');	   	
    }	

    /**
     * Manage gallery action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'gallery_albums', 'galleryAlbums', false);

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
        $this->_view->render('galleryAlbums/manage');
    }
 
    /**
     * Add gallery action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'gallery_albums', 'galleryAlbums', false);
        
        $this->_view->render('galleryAlbums/add');
    }

    /**
     * Edit gallery album action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'gallery_albums', 'galleryAlbums', false);
        $this->_checkAlbumAccess($id);
        
        $this->_view->render('galleryAlbums/edit');
    }

    /**
     * Change status action handler
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 0)
    {
		// Block access if admin has no active privilege to change gallery album lists
        Website::prepareBackendAction('edit', 'gallery_albums', 'galleryAlbums', false);
		$galleryAlbum = $this->_checkAlbumAccess($id);

        if(GalleryAlbums::model()->updateByPk($id, array('is_active'=>($galleryAlbum->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
        }else{
            $alert = ((APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error'));
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
        }
        
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('galleryAlbums/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Delete gallery album action handler
     * @param int $id the gallery album id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'gallery_albums', 'galleryAlbums', false);
        $galleryAlbum = $this->_checkAlbumAccess($id);

        $alert = '';
    	$alertType = '';
    
		if($galleryAlbum->delete()){				
			$alert = A::t('gallery', 'Gallery album deleted successfully');
			$alertType = 'success';	
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';
		   	}else{
				$alert = A::t('gallery', 'Gallery album deleting error');
				$alertType = 'error';
		   	}			
		}
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('galleryAlbums/manage');
    }
 
	/**
	 * Check if passed album ID is valid
	 * @param int $albumId
	 */
	private function _checkAlbumAccess($albumId = 0)
	{        
		$galleryAlbum = GalleryAlbums::model()->findByPk((int)$albumId);
		if(empty($galleryAlbum)){
			$this->redirect('galleryAlbums/manage');
		}
		$this->_view->galleryAlbum = $galleryAlbum;
        return $galleryAlbum;
	}    

}