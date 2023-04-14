<?php
/**
 * TicketReplies controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              
 * indexAction
 * repliesAction
 * userRepliesAction
 *
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

class TicketRepliesController extends CController
{

	private $_backendPath = '';
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
            $this->_view->errorField = '';
			$this->_view->backendPath = $this->_backendPath;
        }

		$this->_view->tabs = TicketsComponent::prepareTab('tickets');

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		$this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->dateFormat = Bootstrap::init()->getSettings('date_format');
		$this->_view->timeFormat = Bootstrap::init()->getSettings('time_format');

		// Set members db table according to settings
		$configModule = CLoader::config('tickets', 'main');
		$this->_memberRole = isset($configModule['ticketMembers']['memberRole']) ? $configModule['ticketMembers']['memberRole'] : 'user';
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('ticketReplies/replies');
    }

    /**
     * Replies to tickets action handler
     * @param int $id
	 * @param string $status
     */
    public function repliesAction($id = 0, $status = '')
    {
        Website::prepareBackendAction('replies', 'tickets', 'tickets/manage/id/'.$id, false);

		if(!in_array($status, array('closed', 'answered', 'waitingreply', 'opened'))){
			$status = '';
		}

		$patchName = 'assets/modules/tickets/uploaded/'.date('Y-m').'/';

		if(!file_exists($patchName)){
            if (!mkdir($patchName) && !is_dir($patchName)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $patchName));
            }
		}
		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		$patchName = 'assets/modules/tickets/uploaded/'.date('Y-m').'/';
		if(!file_exists($patchName)){
            if (!mkdir($patchName) && !is_dir($patchName)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $patchName));
            }
		}

        $modelTickets = Tickets::model()->findByPk($id);
        $modelReplies = TicketReplies::model()->findByAttributes(array('ticket_id' => $id));

		$arrTickets = array();
		$arrTickets[0]['date_created'] = $modelTickets->date_created;
		$arrTickets[0]['message'] = $modelTickets->message;
		$arrTickets[0]['account_role'] = $this->_memberRole;
		$arrTickets[0]['fullname'] = $modelTickets->fullname_user;
		$arrTickets[0]['file'] = $modelTickets->file;
		$arrTickets[0]['file_path'] = 'assets/modules/tickets/uploaded/'.$modelTickets->file_path.'/';

		$arrReplies = array();
		$count = count($modelReplies);
		for($i=0; $i<$count; $i++){
			$arrReplies[$i]['id'] = $modelReplies[$i]['id'];
			$arrReplies[$i]['date_created'] = $modelReplies[$i]['date_created'];
			$arrReplies[$i]['message'] = $modelReplies[$i]['message'];
			$arrReplies[$i]['account_role'] = $modelReplies[$i]['account_role'];
			if($arrReplies[$i]['account_role'] == 'admin'){
				$arrReplies[$i]['fullname'] = $modelReplies[$i]['fullname_admin'];
			}else{
				$arrReplies[$i]['fullname'] = $modelTickets->fullname_user;
			}

			$arrReplies[$i]['file'] = $modelReplies[$i]['file'];
			$arrReplies[$i]['file_path'] = 'assets/modules/tickets/uploaded/'.$modelReplies[$i]['file_path'].'/';
		}
		$arrReplies = array_merge($arrReplies, $arrTickets);
		$arrReplies =  TicketsComponent::sortArr($arrReplies,'date_created');

		$arrDate = array();
		$dateFormat = Bootstrap::init()->getSettings('date_format');
		foreach($arrReplies as $arrTicketsAndAnswer){
			$arrDate[] = date($dateFormat, strtotime($arrTicketsAndAnswer['date_created']));
		}
		$arrDate = array_unique($arrDate);

		$this->_view->subTabs = TicketsComponent::prepareSubTab('tickets', $status);
		
		$this->_view->id = $id;
		$this->_view->arrReplies = $arrReplies;
		$this->_view->modelTickets = $modelTickets;
		$this->_view->arrDate = $arrDate;
		$this->_view->status = $status;
		$this->_view->createPath = date('Y-m');

        $this->_view->render('ticketReplies/replies');
    }

    /**
     * User replies to tickets action handler
     * @param int $id
     */
	public function userRepliesAction($id = 0)
	{
		// Set frontend mode
		Website::setFrontend();

		$modelTickets = Tickets::model()->findByPk($id);
		$modelReplies = TicketReplies::model()->findByAttributes(array('ticket_id' => $id));

		if(!CAuth::getLoggedId() || CAuth::getLoggedRole() != $this->_memberRole){
			$this->redirect('tickets/userManageTickets');
		}elseif($modelTickets){
			if(CAuth::getLoggedId() != $modelTickets->account_id){
				$this->redirect('tickets/userManageTickets');
			}
		}else{
			$this->redirect('tickets/userManageTickets');
		}

		$patchName = 'assets/modules/tickets/uploaded/'.date('Y-m').'/';
		if(!file_exists($patchName)){
			mkdir($patchName);
		}

		if($this->_cSession->hasFlash('alert')){
			$alert = $this->_cSession->getFlash('alert');
			$alertType = $this->_cSession->getFlash('alertType');

			$this->_view->actionMessage = CWidget::create(
				'CMessage', array($alertType, $alert, array('button'=>true))
			);
		}

		$arrTickets = array();
		$arrTickets[0]['date_created'] = $modelTickets->date_created;
		$arrTickets[0]['message'] = $modelTickets->message;
		$arrTickets[0]['account_role'] = $this->_memberRole;
		$arrTickets[0]['fullname'] = $modelTickets->fullname_user;
		$arrTickets[0]['file'] = $modelTickets->file;
		$arrTickets[0]['file_path'] = $modelTickets->file_path;

		$arrReplies = array();
		$count = count($modelReplies);
		for($i=0; $i<$count; $i++){
			$arrReplies[$i]['id'] = $modelReplies[$i]['id'];
			$arrReplies[$i]['date_created'] = $modelReplies[$i]['date_created'];
			$arrReplies[$i]['message'] = $modelReplies[$i]['message'];
			$arrReplies[$i]['account_role'] = $modelReplies[$i]['account_role'];
			if($arrReplies[$i]['account_role'] == 'admin'){
				if($modelReplies[$i]['fullname_admin'] == ' ') $arrReplies[$i]['fullname'] = $modelReplies[$i]['username'];
				else $arrReplies[$i]['fullname'] = $modelReplies[$i]['fullname_admin'];
			}else{
				$arrReplies[$i]['fullname'] = $modelTickets->fullname_user;
			}
			$arrReplies[$i]['file'] = $modelReplies[$i]['file'];
			$arrReplies[$i]['file_path'] = $modelReplies[$i]['file_path'];
		}
		$arrReplies = array_merge ($arrReplies, $arrTickets);
		$arrReplies =  TicketsComponent::sortArr($arrReplies,'date_created');

		$arrDate = array();
		$dateFormat = Bootstrap::init()->getSettings('date_format');
		foreach($arrReplies as $arrTicketsAndAnswer){
			$arrDate[] = date($dateFormat, strtotime($arrTicketsAndAnswer['date_created']));
		}
		$arrDate = array_unique($arrDate);

		$this->_view->id = $id;
		$this->_view->arrReplies = $arrReplies;
		$this->_view->arrDate = $arrDate;
		$this->_view->createPath = date('Y-m');

		$this->_view->render('ticketReplies/userReplies');
	}

	/**
	 * Download attachment to tickets action handler
	 * @param int $ticketId
	 * @param int $replyId
	 */
	public function attachmentAction($ticketId = 0, $replyId = 0)
	{
		Website::setFrontend();
		
		$modelTickets = Tickets::model()->findByPk($ticketId);
		
		// Check if user has right to access ticket
		if(
		   CAuth::isLoggedInAsAdmin() ||
		   (CAuth::getLoggedId() == $modelTickets->account_id && CAuth::getLoggedRole() == $modelTickets->account_role && $ticketId == $modelTickets->id)
		){
			$ticketReply = TicketReplies::model()->find(CConfig::get('db.prefix').'tickets_replies.id = :id AND '.CConfig::get('db.prefix').'tickets_replies.ticket_id = :ticket_id', array('id' => $replyId, 'ticket_id' => $ticketId));
			if(!empty($ticketReply)){
				if(file_exists('assets/modules/tickets/uploaded/'.$ticketReply->file_path.'/'.$ticketReply->file)){
					A::app()->getRequest()->downloadFile(
						'assets/modules/tickets/uploaded/'.$ticketReply->file_path.'/'.$ticketReply->file
					);
				}else{
					$this->_cSession->setFlash('alert', A::t('tickets', 'An error occurred while downloading file! Please try again later.'));
					$this->_cSession->setFlash('alertType', 'error');
					$this->redirect('ticketReplies/userReplies/id/'.$ticketId);
				}
			}
		}
	}
}
