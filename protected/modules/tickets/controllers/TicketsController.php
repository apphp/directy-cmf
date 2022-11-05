<?php
/**
 * Tickets controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct
 * indexAction
 * manageAction
 * editTicketAction
 * deleteTicketAction
 * userAddTicketAction
 * userEditTicketAction
 * userManageTicketsAction
 */

namespace Modules\Tickets\Controllers;

// Modules
use \Modules\Tickets\Components\TicketsComponent,
	\Modules\Tickets\Models\Tickets,
	\Modules\Tickets\Models\TicketReplies;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CLoader,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\Languages,
	\ModulesSettings;

class TicketsController extends CController
{

	private $_backendPath = '';
	private $_memberLoginUrl = array();
	private $_memberRole = '';

    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if module is not installed
        if(!Modules::model()->isInstalled('tickets')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
        }

        if(CAuth::isLoggedIn()){
            // Set meta tags according to active TicketsController
            Website::setMetaTags(array('title'=>A::t('tickets', 'Tickets Management')));

            $this->_view->actionMessage = '';
			$this->_view->accessClosed = '';
            $this->_view->errorField = '';
			$this->_view->backendPath = $this->_backendPath;

            $this->_view->tabs = TicketsComponent::prepareTab('tickets');
			$this->_view->subTabs = TicketsComponent::prepareSubTab('tickets', 'all');
        }

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		$this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->dateFormat = Bootstrap::init()->getSettings('date_format');
		$this->_view->timeFormat = Bootstrap::init()->getSettings('time_format');

		$this->_view->editStatusTicket = array (
			'0' => A::t('tickets', 'Opened'),
			'1' => A::t('tickets', 'Needs reply by Admin'),
			'2' => A::t('tickets', 'Needs reply by User'),
			'3' => A::t('tickets', 'Closed'),
		);
		$this->_view->manageStatusTicket = array(
			'0' => '<span class="label-green badge-square">'.A::t('tickets', 'Opened').'</span>',
			'1' => '<span class="label-yellow badge-square">'.A::t('tickets', 'Needs reply by Admin').'</span>',
			'2' => '<span class="label-gray badge-square">'.A::t('tickets', 'Needs reply by User').'</span>',
			'3' => '<span class="label-red badge-square">'.A::t('tickets', 'Closed').'</span>'
		);

		$this->_view->editDepartmentsTicket = array (
			'0' => A::t('tickets', 'General'),
			'1' => A::t('tickets', 'Technical Issues'),
			'2' => A::t('tickets', 'Billing'),
			'3' => A::t('tickets', 'Security'),
		);

		$configModule = CLoader::config('tickets', 'main');
		$this->_memberLoginUrl = isset($configModule['ticketMembers']['loginUrl']) ? $configModule['ticketMembers']['loginUrl'] : 'users/login';
		$this->_memberRole = isset($configModule['ticketMembers']['memberRole']) ? $configModule['ticketMembers']['memberRole'] : 'user';
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('tickets/manage');
    }

    /**
     * Manage action handler
     * @param string $status
     */
    public function manageAction($status = '')
    {
        Website::prepareBackendAction('manage', 'tickets', 'tickets/manage', false);

		$emailAdmin = ModulesSettings::model()->param('tickets', 'general_admin_email');
		if(!$emailAdmin){
			$alert = A::t('tickets', 'Please specify the general admin email in the module settings', array('{SITE_BO_URL}'=>$this->_backendPath));
			$alertType = 'warning';
			if(!empty($alert)){
				$this->_view->actionMessage = CWidget::create(
					'CMessage', array($alertType, $alert, array('button'=>false))
				);
			}
		}

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');

            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}
		
		if(!in_array($status, array('closed', 'answered', 'waitingreply', 'opened'))){
			$status = '';
		}
		
		if($status == 'closed'){
			$statusCode = 3;
		}elseif($status == 'answered'){
			$statusCode = 2;
		}elseif($status == 'waitingreply'){
			$statusCode = 1;
		}elseif($status == 'opened'){
			$statusCode = 0;
		}else{
			$statusCode = '';
			$status = '';
		}
		
		$this->_view->status = $status;	
		$this->_view->statusCode = $statusCode;	
		
		$this->_view->subTabs = TicketsComponent::prepareSubTab('tickets', $status);	
        $this->_view->render('tickets/manage');
    }

    /**
     * Tickets edit handler
     * @param int $id
     * @param string $status
     */
    public function editTicketAction($id = 0, $status = '')
    {
        Website::prepareBackendAction('editTicket', 'tickets', 'tickets/manage/', false);

        $model = Tickets::model()->findByPk($id);

		if(!in_array($status, array('closed', 'answered', 'waitingreply', 'opened'))){
			$status = '';
		}
		
        $this->_view->tickets = $model;
		$this->_view->status = $status;	
        $this->_view->render('tickets/editTicket');
    }

    /**
     * Delete action handler
     * @param int $id
     * @param string $status
     */
    public function deleteTicketAction($id = 0, $status = '')
    {
        Website::prepareBackendAction('deleteTicket', 'tickets', 'tickets/manage', false);
        $model = Tickets::model()->findByPk($id);

		if(!in_array($status, array('closed', 'answered', 'waitingreply', 'opened'))){
			$status = '';
		}

        $alert = '';
        $alertType = '';

        if($model->delete()){
            if($model->getError()){
                $alert = A::t('app', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert = A::t('tickets', 'Ticket successfully deleted');
                $alertType = 'success';
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
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect('tickets/manage'.(!empty($status) ? '/status/'.$status : ''));
    }

	/**
	 * Manage Frontend action handler
	 */
	public function userManageTicketsAction($type = '')
	{
		Website::setFrontend();

		if(!CAuth::getLoggedId() || CAuth::getLoggedRole() != $this->_memberRole){

			if(CAuth::isLoggedIn() && CAuth::getLoggedRole() != $this->_memberRole){
				$this->redirect('tickets/manage');
			}
			
			$alert = A::t('tickets', 'You must be logged to to view the requested page!', array('{MEMBERS_LOGIN_URL}'=>$this->_memberLoginUrl));
			$alertType = 'warning';
			if(!empty($alert)){
				$this->_view->accessClosed = CWidget::create(
					'CMessage', array($alertType, $alert, array('button'=>false))
				);
			}
		}

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		$this->_view->render('tickets/userManageTickets');
	}

	/**
	 * Frontend Create ticket action handler
	 */
	public function userAddTicketAction()
	{
		Website::setFrontend();

		if(!CAuth::getLoggedId() || CAuth::getLoggedRole() != $this->_memberRole){
			$this->redirect('tickets/userManageTickets');
		}

		$patchName = 'assets/modules/tickets/uploaded/'.date('Y-m').'/';

		if(!file_exists($patchName)){
			mkdir($patchName);
		}
		$this->_view->createPath = date('Y-m');
		$this->_view->render('tickets/userAddTicket');
	}

	/**
	 * Frontend Ticket edit handler
	 * @param int $id
	 */
	public function userEditTicketAction($id = 0)
	{
		Website::setFrontend();

		$model = Tickets::model()->findByPk($id);

		if(!CAuth::getLoggedId() || CAuth::getLoggedRole() != $this->_memberRole){
			$this->redirect('tickets/userManageTickets');
		}elseif($model){
			if(CAuth::getLoggedId() != $model->account_id){
				$this->redirect('tickets/userManageTickets');
			}
		}else{
			$this->redirect('tickets/userManageTickets');
		}

		$this->_view->tickets = $model;
		$this->_view->render('tickets/userEditTicket');
	}
}
