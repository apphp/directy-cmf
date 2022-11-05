<?php

/**
 * EventsCategories controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkCategoryAccess
 * indexAction              
 * manageAction             
 * addAction
 * editAction
 * deleteAction
 * changeStatusAction
 * 
 */

namespace Modules\Events\Controllers;

// Modules
use \Modules\Events\Components\EventsComponent,
	\Modules\Events\Models\EventsCategories,
	\Modules\Events\Models\Events;

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

class EventsCategoriesController extends CController {

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
		if(!Modules::model()->isInstalled('events')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
		}

        if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active language
            Website::setMetaTags(array('title' => A::t('events', 'Events Categories Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->tabs = EventsComponent::prepareTab('eventscategories');
        }

        $this->_cSession = A::app()->getSession();

        $this->_settings = Bootstrap::init()->getSettings();
        $this->_view->backendPath = $this->_backendPath;
    }

    /**
     * Controller default action handler
     */
    public function indexAction() {
        $this->redirect('eventsCategories/manage');
    }

    /**
     * Manage events categories action handler
     */
    public function manageAction(){
        Website::prepareBackendAction('manage', 'events', 'eventsCategories/manage');

        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->render('eventsCategories/manage');
    }

    /**
     * Events Category change status
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id = 0, $page = 1) {
        Website::prepareBackendAction('edit', 'events', 'eventsCategories/manage');
        $eventsCategory = $this->_checkCategoryAccess($id);

        $changeResult = EventsCategories::model()->updateByPk($id, array('event_category_is_active' => ($eventsCategory->event_category_is_active == 1 ? '0' : '1')));
        if ($changeResult) {
            $alert = A::t('events', 'Events Category status has been successfully changed!');
            $alertType = 'success';
        } else {
			$alert = APPHP_MODE == 'demo' ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('events', 'Events Category status cannot be changed!');
			$alertType = APPHP_MODE == 'demo' ? 'warning' : 'error';
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
 
        $this->redirect('eventsCategories/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Add events category action handler
     */
    public function addAction() {
        Website::prepareBackendAction('add', 'events', 'eventsCategories/manage');

        $this->_view->dateTimeFormat = $this->_settings->datetime_format;
        $this->_view->dateFormat = $this->_settings->date_format;
        $this->_view->timeFormat = $this->_settings->time_format;

        $this->_view->render('eventsCategories/add');
    }

    /**
     * Edit events category action handler
     * @param int $id
     */
    public function editAction($id = 0) {
        Website::prepareBackendAction('edit', 'events', 'eventsCategories/manage');
        $this->_view->eventsCategory = $this->_checkCategoryAccess($id);

        $this->_view->render('eventsCategories/edit');
    }

    /**
     * Delete events category action handler
     * @param int $id
     */
    public function deleteAction($id = 0) {
        Website::prepareBackendAction('delete', 'events', 'eventsCategories/manage');
        $events = $this->_checkCategoryAccess($id);

        $alert = '';
        $alertType = '';

        if ($events->delete()) {
            $alert = A::t('events', 'Events category deleted successfully');
            $alertType = 'success';
        } else {
            if (APPHP_MODE == 'demo') {
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            } else {
                $alert = A::t('events', 'Events category deleting error');
                $alertType = 'error';
            }
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('eventsCategories/manage');
    }

    /**
     * Check if passed category ID is valid
     * @param int $catId
     */
    private function _checkCategoryAccess($catId = 0) {
        $eventsCategory = EventsCategories::model()->findByPk((int) $catId);
        if (empty($eventsCategory)) {
            $this->redirect('eventsCategories/manage');
        }
        return $eventsCategory;
    }

}
