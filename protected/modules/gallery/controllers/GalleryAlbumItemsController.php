<?php
/**
 * GalleryAlbumItems controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------         		------------------
 * __construct              _checkAlbumAccess
 * indexAction              _checkAlbumItemAccess
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

class GalleryAlbumItemsController extends CController
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
    		Website::setMetaTags(array('title'=>A::t('gallery', 'Gallery Album Items Management')));
           
			$this->_cRequest = A::app()->getRequest();
			$this->_cSession = A::app()->getSession();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
        }    
    }

	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		$this->redirect('galleryAlbumItems/manage');	   	
    }	

    /**
     * Manage gallery items action handler
     * @param int $albumId
     */
    public function manageAction($albumId = '')
    {
        Website::prepareBackendAction('manage', 'gallery_albums', 'galleryAlbumItems/manage/albumId/'.$albumId, false);
        $this->_checkAlbumAccess($albumId);       
       
		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
        $this->_view->render('galleryAlbumItems/manage');        
    }
 
    /**
     * Add gallery item action handler
     * @param int $albumId
     */
    public function addAction($albumId = 0)
    {
        Website::prepareBackendAction('add', 'gallery_albums', 'galleryAlbumItems/manage/albumId/'.$albumId, false);
        $this->_checkAlbumAccess($albumId);
        
        $this->_view->render('galleryAlbumItems/add');
    }

    /**
     * Edit gallery album item action handler
     * @param int $albumId
     * @param int $id
     * @param string $image has value 'delete' in order to delete the item image file
     */
    public function editAction($albumId = 0, $id = 0, $image = '')
    {
        Website::prepareBackendAction('edit', 'gallery_albums', 'galleryAlbumItems/manage/albumId/'.$albumId, false);
        $this->_checkAlbumAccess($albumId);
        $galleryAlbumItem = $this->_checkAlbumItemAccess($albumId, $id);

    	// Delete the image file
        if($image === 'delete'){
        	$alert = '';
        	$alertType = '';
        	$image = 'assets/modules/gallery/images/items/'.$galleryAlbumItem->item_file;
            $imageThumb = 'assets/modules/gallery/images/items/'.$galleryAlbumItem->item_file_thumb;
        	$galleryAlbumItem->item_file = '';
            $galleryAlbumItem->item_file_thumb = '';
        	// Save the changes in gallery album items table
        	if($galleryAlbumItem->save()){
        		// Delete the images
        		if(CFile::deleteFile($image) && CFile::deleteFile($imageThumb)){
        			$alert = A::t('gallery', 'Image successfully deleted');
        			$alertType = 'success';
        		}else{
        			$alert = A::t('gallery', 'Image deleting warning');
        			$alertType = 'warning';
        		}
        	}else{
        		$alert = A::t('gallery', 'Image deleting error');
        		$alertType = 'error';
        	}
        	if(!empty($alert)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        	}
        }

		$this->_view->galleryAlbumItem = $galleryAlbumItem;		
        $this->_view->render('galleryAlbumItems/edit');
    }

    /**
     * Change status action handler
     * @param int $albumId
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($albumId = 0, $id = 0, $page = 0)
    {
        Website::prepareBackendAction('edit', 'gallery_albums', 'galleryAlbumItems/manage/albumId/'.$albumId, false);
        $this->_checkAlbumAccess($albumId);
        $galleryAlbumItem = $this->_checkAlbumItemAccess($albumId, $id);

        if(GalleryAlbumItems::model()->updateByPk($id, array('is_active'=>($galleryAlbumItem->is_active == 1 ? '0' : '1')))){
			$alert = A::t('app', 'Status has been successfully changed!');
			$alertType = 'success';
        }else{
            $alert = ((APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error'));
			$alertType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
        }
        
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('galleryAlbumItems/manage/albumId/'.$albumId.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Delete album item  action handler
     * @param int $albumId
     * @param int $id
     */
    public function deleteAction($albumId = 0, $id = 0)
    {
        Website::prepareBackendAction('delete', 'gallery_albums', 'galleryAlbumItems/manage/albumId/'.$albumId, false);
        $this->_checkAlbumAccess($albumId);
        $galleryAlbumItem = $this->_checkAlbumItemAccess($albumId, $id);

        $alert = '';
    	$alertType = '';    
        $image = 'assets/modules/gallery/images/items/'.$galleryAlbumItem->item_file;
        $imageThumb = 'assets/modules/gallery/images/items/'.$galleryAlbumItem->item_file_thumb;
		
        if($galleryAlbumItem->delete()){
        	$alert = A::t('gallery', 'Gallery album item deleted successfully');			
			// Delete the icon file
        	if(CFile::deleteFile($image) && CFile::deleteFile($imageThumb)){
        		$alertType = 'success';	
        	}else{
        		$alert .= '<br>'.A::t('gallery', 'Gallery album item delete image warning');
        		$alertType = 'warning';
        	}			
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = CDatabase::init()->getErrorMessage();
				$alertType = 'warning';
		   	}else{
				$alert = A::t('gallery', 'Gallery album item deleting error');
				$alertType = 'error';
		   	}			
		}
		
 		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);
		
		$this->redirect('galleryAlbumItems/manage/albumId/'.$albumId);
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
		$this->_view->albumId = $albumId;
		$this->_view->albumTitle = $galleryAlbum->album_title;
        $this->_view->albumLink = 'galleryAlbumItems/manage/albumId/'.$albumId;
        
        $this->_view->tabs = GalleryComponent::prepareTab('galleryitems', $albumId);		
	}
    
	/**
	 * Check if passed album item ID is valid
	 * @param int $albumId
	 * @param int $id
	 */
	private function _checkAlbumItemAccess($albumId = 0, $id = 0)
	{
		$galleryAlbumItem = GalleryAlbumItems::model()->findByPk($id);
		if(!$galleryAlbumItem){
			$this->redirect('galleryAlbumItems/manage/albumId/'.$albumId);
		}
        return $galleryAlbumItem;
    }    

}