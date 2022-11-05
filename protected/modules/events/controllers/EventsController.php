<?php

/**
 * Events controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _loadEventsCategory
 * indexAction              _loadEvent
 * manageAction             
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * getEventsCalendarAction
 * showCalendarAction
 * 
 */

namespace Modules\Events\Controllers;

// Modules
use \Modules\Events\Components\EventsComponent,
	\Modules\Events\Models\Events,
	\Modules\Events\Models\EventsCategories;

// Framework
use \A,
	\CAuth,
	\CConfig,
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


class EventsController extends CController {

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

        // Set meta tags according to active language
        Website::setMetaTags(array('title' => A::t('events', 'Events Management')));

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
        $this->redirect('events/manage');
    }

    /**
     * Manage events action handler
     * @param int $catId 
     */
    public function manageAction($catId = '')
	{
        Website::prepareBackendAction('manage', 'events', 'events/manage');
        $this->_loadEventsCategory($catId);

        if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->render('events/manage');
    }

    /**
     * Add events action handler
     * @param int $catId 
     */
    public function addAction($catId = 0) {
        Website::prepareBackendAction('add', 'events', 'events/manage');
        $this->_loadEventsCategory($catId);

        $this->_view->render('events/add');
    }

    /**
     * Edit events action handler
     * @param int $catId
     * @param int $id
     */
    public function editAction($catId = 0, $id = 0)
	{
        Website::prepareBackendAction('edit', 'events', 'events/manage');
        $this->_loadEventsCategory($catId);

        $this->_view->event = $this->_loadEvent($id, $catId);
        $this->_view->render('events/edit');
    }

    /**
     * Event  change status
     * @param int $id
     * @param int $catId
     * @param int $page 	the page number
     */
    public function changeStatusAction($id = 0, $catId = 0, $page = 1)
	{		
        Website::prepareBackendAction('edit', 'events', 'events/manage');
        $events = $this->_loadEvent($id, $catId);

        $changeResult = Events::model()->updateByPk($id, array('event_is_active' => ($events->event_is_active == 1 ? '0' : '1')));
        if ($changeResult) {
            $alert = A::t('events', 'Event status has been successfully changed!');
            $alertType = 'success';
        }else{
            $alert = APPHP_MODE == 'demo' ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('events', 'Event status cannot be changed!');
            $alertType = APPHP_MODE == 'demo' ? 'warning' : 'error';
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('events/manage/catId/'.$catId.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Delete events action handler
     * @param int $catId
     * @param int $id
     */
    public function deleteAction($catId = 0, $id = 0)
	{
        Website::prepareBackendAction('delete', 'events', 'events/manage');
        $this->_loadEventsCategory($catId);
        $events = $this->_loadEvent($id, $catId);

        $alert = '';
        $alertType = '';

        if ($events->delete()) {
            $alert = A::t('events', 'Events deleted successfully');
            $alertType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = $events->getError() ? $events->getErrorMessage() : A::t('events', 'Events deleting error');
                $alertType = 'error';
            }
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('events/manage/catId/'.$catId);
    }

    /**
     * View all events on frontend
     */
    public function showCalendarAction() {
        // Set frontend mode
        Website::setFrontend();

        $this->_view->timezone = Bootstrap::init()->getSettings('time_zone');
        $this->_view->currentLangCode = A::app()->getLanguage();


        $this->_view->categories = EventsCategories::loadEventsCategories();

        $this->_view->url = 'events/getEventsCalendar';
        $this->_view->render('events/showCalendar');
    }

    /**
     * Reply to AJAX request from fullcalendar.js
     */
    public function getEventsCalendarAction()
	{

        $dateStart = A::app()->getRequest()->getQuery('start');
        if ($dateStart == '')
            $dateStart = date('Y-m-d');
        $dateEnd = A::app()->getRequest()->getQuery('end');
        if ($dateEnd == '')
            $dateEnd = date('Y-m-d');

        $timezone = A::app()->getRequest()->getQuery('timezone');

        $catId = A::app()->getRequest()->getQuery('catId');
        $catId = intval($catId);
        $sqlCondition = "";
        if ($catId > 0) {
            $sqlCondition = " AND ".CConfig::get('db.prefix')."events.event_category_id=$catId";
        }
        $events = Events::model()->findAll(array(
            'condition' => "event_is_active = 1 AND (
               ( event_starts_at between '$dateStart' AND '$dateEnd' )
            OR
               ( event_ends_at between '$dateStart' AND '$dateEnd' )
            OR
               ( '$dateStart' between event_starts_at AND event_ends_at )
            OR
               ( '$dateEnd' between event_starts_at AND event_ends_at )
             )$sqlCondition"
        ));

        echo json_encode(array_map(function($in) {
                    $out = array(
                        'id' => $in['id']
                        , 'title' => $in['event_name']
                        , 'description' => $in['event_description']
                        , 'start' => date('Y-m-d\TH:i:s', strtotime($in['event_starts_at']))
                        , 'end' => date('Y-m-d\TH:i:s', strtotime($in['event_ends_at']))
                    );
                    if ($in['event_link_url']) {
                        $out['url'] = $in['event_link_url'];
                    }
                    return $out;
                }, $events));
        exit();
    }
   
    /**
     * Check if passed category ID is valid
     * @param int $catId
     */
    private function _loadEventsCategory($catId = 0)
	{
        $eventsCategory = EventsCategories::model()->findByPk((int) $catId);
        if (empty($eventsCategory)) {
            $this->redirect('eventsCategories/manage');
        }

        $this->_view->catId = $catId;
        $this->_view->categoryName = $eventsCategory->event_category_name;
        $this->_view->categoryLink = 'events/manage/catId/'.$catId;
        $this->_view->tabs = EventsComponent::prepareTab('events', $catId);

        return $eventsCategory;
    }

    /**
     * Check if passed event ID is valid
     * @param int $id
     * @param int $catId
     */
    private function _loadEvent($id = 0, $catId = 0)
	{
        $event = Events::model()->findByPk($id);
        if (!$event) {
            $this->redirect('events/manage/catId/'.$catId);
        }

        return $event;
    }

}
