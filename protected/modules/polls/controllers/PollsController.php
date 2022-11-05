<?php
/**
 * Polls controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkActionAccess
 * indexAction
 * manageAction
 * addAction
 * editAction
 * changeStatusAction
 * deleteAction
 * showAction
 * showAllAction
 * voteAction
 *
 */

namespace Modules\Polls\Controllers;

// Modules
use \Modules\Polls\Components\PollsComponent,
	\Modules\Polls\Models\Polls,
	\Modules\Polls\Models\Votes;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CFile,
	\CLoader,
	\CLocale,
	\CTime,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\ModulesSettings;


class PollsController extends CController
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
		if(!Modules::model()->isInstalled('polls')){
            if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
            }else{
                $this->redirect(Website::getDefaultPage());
            }
        }

        if(CAuth::isLoggedInAsAdmin()) {
            // Set meta tags according to active polls
            Website::setMetaTags(array('title'=>A::t('polls', 'Polls Management')));
            // Set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
			$this->_view->backendPath = $this->_backendPath;

            $this->_cRequest = A::app()->getRequest();
            $this->_cSession = A::app()->getSession();

            $this->_view->arrViewType = array('0'=>A::t('polls', 'Equally'), '1'=>A::t('polls', 'Substring'));
            $this->_view->tabs = PollsComponent::prepareTab('polls');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('polls/manage');
    }

    /**
     * Manage action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'polls', 'polls/manage', false);

        $actionMessage = '';

        $alert = $this->_cSession->getFlash('alert');
        $alertType = $this->_cSession->getFlash('alertType');

        if(!empty($alert)){
            $actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->actionMessage = $actionMessage;
        $this->_view->render('polls/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'polls', 'polls/manage', false);

        $this->_view->render('polls/add');
    }

    /**
     * Edit polls action handler
     * @param int $id
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'polls', 'polls/manage', false);

        $poll = $this->_checkActionAccess($id);

        $this->_view->poll = $poll;
        $this->_view->render('polls/edit');
    }

    /**
     * Change poll state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('edit', 'polls', 'banners/manage', false);

        $poll = $this->_checkActionAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			$changeResult = Polls::model()->updateByPk($id, array('is_active' => ($poll->is_active == 1 ? 0 : 1)));
			if($changeResult){
				$alert = A::t('polls', 'Poll status has been successfully changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('polls', 'An error occurred while changing poll status! Please try again later.');
				$alertType = 'error';
			}
		}

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('polls/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Delete action handler
     * @param int $id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'polls', 'polls/manage', false);
        $model = $this->_checkActionAccess($id);

        // Check if default
        if($model->is_default){
            $alert = A::t('polls', 'Default poll cannot be removed!');
            $alertType = 'error';
        }elseif($model->delete()){
            $alert = A::t('polls', 'Poll has been successfully deleted!');
            $alertType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $alertType = 'warning';
            }else{
                $alert = $model->getError() ? $model->getErrorMessage() : A::t('polls', 'An error occurred while deleting poll! Please try again later.');
                $alertType = 'error';
            }
        }

		if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
		}
		
        $this->redirect('polls/manage');
    }

    public function showAllAction()
    {
        // Set frontend mode
        Website::setFrontend();
        $previousPolls = Polls::model()->findAll(array('order'=>'created_at DESC'));
        if(empty($previousPolls)){
            $this->redirect(Website::getDefaultPage());
        }
        $this->_view->previousPolls = $previousPolls;
        $this->_view->render('polls/showall');
    }

    /**
     * Controller view news description
     * @param int $id
     */
    public function showAction($id = 0)
    {
        $isAjax = A::app()->getRequest()->isAjaxRequest() == true ? true : false;
        $poll = Polls::model()->findByPk((int)$id);

        if($isAjax){
            if($poll){
                $html  = PollsComponent::getResultPoll($poll->getFieldsAsArray());
                $html .= '<a class="bold" href="polls/vote/id/'.$poll->id.'" data-type="vote">'.A::te('polls', 'Vote').'</a>';
                $html .= '<br/>';
                $html .= '<a class="polls-archive" href="polls/vote/id/'.$poll->id.'">'.A::te('polls', 'Polls Archive').'</a>';
                $output = '{"html": "'.str_replace(array('"', "\r\n", "\n\r", "\n", "\r", "\t"), array("'", '', '', '', '', ''), $html).'"}';
            }else{
                $output = '{"error_message": "'.str_replace(array('"', "\r\n", "\n\r", "\n", "\r", "\t"), array("'", '', '', '', '', ''), A::t('polls', 'Poll is not found')).'"}';
            }

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
            header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
            header('Pragma: no-cache'); // HTTP/1.0
            header('Content-Type: application/json');
            echo $output;
            exit();
        }else{
            // Set frontend mode
            Website::setFrontend();

            if($poll){
                $this->_view->errorMessage = A::app()->getSession()->getFlash('errorMessage', null);
                $this->_view->successMessage = A::app()->getSession()->getFlash('successMessage', null);
                $this->_view->poll = $poll;
                $this->_view->previousPolls = Polls::model()->findAll(array('condition'=>''.CConfig::get('db.prefix').'polls.id != :id', 'order'=>'created_at ASC'), array('i:id'=>$poll->id));

                $this->_view->render('polls/show');
            }else{
                $this->redirect('error/index');
            }
        }
    }

    /**
     * Show only poll for widget action handler
     * @param int $id
     */
    public function widgetAction($id = 0)
    {
        $this->_view->baseUrl = A::app()->getRequest()->getBaseUrl();
        $poll = Polls::model()->findByPk($id, 'is_active = 1');
        if(!empty($poll)){
            $this->_view->colorText = $poll->color_text;
            $this->_view->backgroundColor = $poll->background_color;
            $this->_view->widget = PollsComponent::drawPollsBlock('', '', true, $id, true);
            if($this->_view->widget != ''){
                $this->_view->render('polls/widget', true);
            }
        }
    }

    /**
     * Vote poll action handler
     * @param int $id
     */
    public function voteAction($id = 0)
    {
        $isAjax = A::app()->getRequest()->isAjaxRequest() == true ? true : false;

        $poll = $this->_checkActionAccess($id);
        $answerNumber = A::app()->getRequest()->getPost('answer', null, 0);
		
        $alert = '';
        $alertName = '';
        $voterType = ModulesSettings::model()->param('polls', 'voter_type');

		$configModule = CLoader::config('polls', 'main');
		$memberInfo = $configModule['members'];
		$userTypes = array_keys($memberInfo);

        if(APPHP_MODE == 'demo'){
            $alert = A::t('core', 'This operation is blocked in Demo Mode!');
        }elseif($voterType == 'registered_only' && !in_array(CAuth::getLoggedRole(), $userTypes)){
            $alert = A::t('polls', 'You must be registered to vote');
        }elseif(!PollsComponent::checkVotionPermission($id)){
            $alert = A::t('polls', "You don't have permissions to vote");
        }elseif(PollsComponent::hasUserVoteAlready($id)){
            $alert = A::t('polls', 'You have already voted on this poll!');
        }elseif(!$answerNumber){
            $alert = A::t('polls', 'No answer selected. Please select your variant.');
        }

        if($alert){
            $alertName = 'errorMessage';
        }else{
            $answerNumber = A::app()->getRequest()->getPost('answer', null, 0);

            if(in_array($answerNumber, array(1, 2, 3, 4, 5))){
                $poll->{'poll_answer_' . $answerNumber . '_votes'}++;
                $res = $poll->save();
                PollsComponent::markPollAsVoted($id);
                $alert = A::t('polls', 'Your vote accepted');
                $alertName = 'successMessage';
            }else{
                $alert = A::t('polls', 'No answer selected. Please select your variant.');
                $alertName = 'errorMessage';
            }
        }

        if(!$isAjax){
            if(!empty($alert)){
                A::app()->getSession()->setFlash($alertName, $alert);
            }
            $this->redirect('polls/show/id/'.$id);
        }else{
            if($alertName == 'successMessage'){
                $html  = PollsComponent::getResultPoll($poll->getFieldsAsArray());
                //$html .= '<a class="bold" href="polls/show/id/'.$poll->id.'">'.A::t('polls', 'Results').'</a>';
                $output = '{"html": "'.str_replace(array('"', "\r\n", "\n\r", "\n", "\r", "\t"), array("'", '', '', '', '', ''), $html).'"}';
            }else{
                $output = '{"error_message": "'.str_replace(array('"', "\r\n", "\n\r", "\n", "\r", "\t"), array("'", '', '', '', '', ''), $alert).'"}';
            }

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
            header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
            header('Pragma: no-cache'); // HTTP/1.0
            header('Content-Type: application/json');
            echo $output;
            exit();
        }
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Polls
     */
    private function _checkActionAccess($id = 0)
    {
        $poll = Polls::model()->findByPk($id);
        if(!$poll){
            $this->redirect('polls/manage');
        }
        return $poll;
    }
}
