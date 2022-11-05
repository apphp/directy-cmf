<?php
/*
 * NewsSubscribers controller
 *
 * PUBLIC:                  PRIVATE:                    
 * ---------------          ---------------             
 * __construct              _checkSubscribeAccess
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 *
 */

namespace Modules\Webforms\Controllers;

// Modules
use \Modules\Webforms\Components\WebformsComponent;
//	\Modules\News\Models\News,
//	\Modules\News\Models\NewsSubscribers;

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


class WebformsMessagesController extends CController
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
        if(!Modules::model()->isInstalled('webforms')){
            if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
            }else{
                $this->redirect(Website::getDefaultPage());
            }
        }

        $this->_view->actionMessage = '';
        $this->_view->errorField    = '';
		$this->_view->backendPath = $this->_backendPath;

		$this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;

        // Fetch site settings info
		$this->_view->tabs = WebformsComponent::prepareTab('messages');
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
		$this->redirect('webformsMessages/manage');
    }
    
    /**
     * Manage subscription action handler
     * @return void
     */
    public function manageAction()
    {
    	Website::prepareBackendAction('manage', 'webformMessages', 'webformsMessages/manage', false);

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if($alert){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->render('webformsMessages/manage');
    }
    
    /**
     * Add news action handler
     * @return void
     */
    public function addAction()
    {
		Website::prepareBackendAction('add', 'webformMessages', 'webformsMessages/manage', false);

//        $this->_view->created_at = LocalTime::currentDateTime();

		$this->_view->render('webformsMessages/add');
    }

    /**
     * Subscribe edit action handler
     * @return void
     */
    public function editAction($id = 0)
    {
		Website::prepareBackendAction('edit', 'webformMessages', 'webformsMessages/manage', false);
//
//        $subscribe = $this->_checkSubscribeAccess($id);
//        $this->_view->id = $subscribe->id;
//
//        $this->_view->render('newsSubscribers/edit');
    }

    /**
     * Delete subscribe action handler
     * @param int $id the subscribe id
     * @return void
     */
    public function deleteAction($id = 0)
    {
//        Website::prepareBackendAction('delete', 'news', 'newsSubscribers/manage');
//        $subscribe = $this->_checkSubscribeAccess($id);
//
//        if($subscribe->delete()){
//            $alert     = A::t('news', 'Subscriber successfully deleted');
//            $alertType = 'success';
//        }else{
//            if(APPHP_MODE == 'demo'){
//                $alert     = CDatabase::init()->getErrorMessage();
//                $alertType = 'warning';
//            }else{
//                $alert     = A::t('news', 'Error remove subscriber');
//                $alertType = 'error';
//            }
//        }
//
//        if(!empty($alert)){
//			A::app()->getSession()->setFlash('alert', $alert);
//			A::app()->getSession()->setFlash('alertType', $alertType);
//        }
//
//        $this->redirect('newsSubscribers/manage');
    }

    /**
     * Check if passed subscription ID is valid
     * @param int $subscriberId
     * @return NewsSubscribers
     */
    private function _checkSubscribeAccess($subscriberId = 0)
    {
//        $subscriber = NewsSubscribers::model()->findByPk((int)$subscriberId);
//        if(!$subscriber){
//            $this->redirect('newsSubscribers/manage');
//        }
//        return $subscriber;
    }

}
