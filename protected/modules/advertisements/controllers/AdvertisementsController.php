<?php
/**
 * Advertisements controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkAdvertisementAccess
 * indexAction
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 *
 */

namespace Modules\Advertisements\Controllers;

use \Modules\Advertisements\Components\AdvertisementsComponent,
    \Modules\Advertisements\Models\Advertisements;

// Framework
use \A,
    \CAuth,
    \CConfig,
    \CController,
    \CWidget,
    \CFile,
    \CDatabase;

// Aplication
use \Bootstrap,
    \Modules,
    \Website;


class AdvertisementsController extends CController
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
		if(!Modules::model()->isInstalled('advertisements')){
            if(CAuth::isLoggedInAsAdmin()){
                $this->redirect($this->_backendPath.'modules/index');
            }else{
				$this->redirect(Website::getDefaultPage());
            }
        }

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

        if(CAuth::isLoggedInAsAdmin()) {
            // Set meta tags according to active advertisements
            Website::setMetaTags(array('title'=>A::t('advertisements', 'Advertisements Management')));
            // Set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_cRequest = A::app()->getRequest();
            $this->_cSession = A::app()->getSession();

            $activeMenu = CConfig::get('modules.advertisements.backendDefaultUrl');
            $this->_view->activeMenu = !empty($activeMenu) ? $activeMenu : $this->_backendPath.'modules/settings/code/advertisements';
            $this->_view->tabs = AdvertisementsComponent::prepareTab('advertisements');
        }

        $settings = Bootstrap::init()->getSettings();
        $this->_view->dateFormat     = $settings->date_format;
        //$this->_view->dateTimeFormat = $settings->datetime_format;
        //$this->_view->numberFormat   = $settings->number_format;
        //$this->_view->currencySymbol = A::app()->getCurrency('symbol');
        //$this->_view->currencyRate = A::app()->getCurrency('rate');

		$this->_view->backendPath = $this->_backendPath;
        $this->_view->arrType = array('0'=>A::t('advertisements', 'Image'), '1'=>A::t('advertisements', 'Text'));
        $this->_view->arrTypeComparison = array('0'=>A::t('advertisements', 'Equally'), '1'=>A::t('advertisements', 'Substring'));
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('advertisements/manage');
    }

    /**
     * Manage action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'advertisement', 'modules/index', false);

        $actionMessage = '';

        $alert = $this->_cSession->getFlash('alert');
        $alertType = $this->_cSession->getFlash('alertType');

        if(!empty($alert)){
            $actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->actionMessage = $actionMessage;
        $this->_view->render('advertisements/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'advertisement', 'advertisements/manage', false);

        $this->_view->render('advertisements/add');
    }

    /**
     * Edit advertisements action handler
     * @param int $id
     */
    public function editAction($id = 0, $delete = '')
    {
        Website::prepareBackendAction('edit', 'advertisement', 'advertisements/manage', false);

        $actionMessage = '';
        $advertisement = $this->_checkAdvertisementAccess($id);

        if($delete == 'image' && $advertisement->image != ''){
            $imagePath = 'assets/modules/advertisements/images/items/';
            $imageName = $advertisement->image;
            $imageFullPath = $imagePath.$imageName;
            $advertisement->image = '';

            if($advertisement->save()){
                // Delete the main_image
                if(CFile::deleteFile($imageFullPath)){
                    $alert = A::t('advertisements', 'Image successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('advertisements', 'Image Delete Warning');
                    $alertType = 'warning';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                    $alertType = 'warning';
                }else{
                    if($advertisement->getErrorMessage()){
                        $alert = $advertisement->getErrorMessage();
                        $alertType = 'error';
                    }else{
                        $alert = A::t('shoppingcart', 'Image Delete Warning');
                        $alertType = 'error';
                    }
                }
            }
        }

        if(!empty($alert)){
            $actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->actionMessage = $actionMessage;
        $this->_view->id = $advertisement->id;
        $this->_view->render('advertisements/edit');
    }

    /**
     * Delete action handler
     * @param int $id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'advertisement', 'advertisements/manage', false);
        $advertisement = $this->_checkAdvertisementAccess($id);

        // Check if default
        if($advertisement->delete()){
            if($advertisement->getError()){
                $alert = $advertisement->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('app', 'Delete Success Message');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $alertType = 'warning';
            }else{
                $alert = $advertisement->getError() ? $advertisement->getErrorMessage() : A::t('advertisements', 'An error occurred while deleting advertisement! Please try again later.');
                $alertType = 'error';
            }
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('advertisements/manage');
    }

    /**
     * Change advertisement state method
     * @param int $id 		
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('edit', 'advertisement', 'advertisements/manage', false);

        $ads = $this->_checkAdvertisementAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			if(Advertisements::model()->updateByPk($id, array('is_active' => ($ads->is_active == 1 ? 0 : 1)))){
				$alert = A::t('advertisements', 'Advertisement status has been successfully changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('advertisements', 'An error occurred while changing advertisement status! Please try again later.');
				$alertType = 'error';
			}
		}

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('advertisements/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Advertisements
     */
    private function _checkAdvertisementAccess($id = 0)
    {
        $advertisement = Advertisements::model()->findByPk($id);
        if(!$advertisement){
            $this->redirect('advertisements/manage');
        }
        return $advertisement;
    }
}
