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
 * subscribeAction
 * unsubscribeAction
 */

namespace Modules\News\Controllers;

// Modules
use \CValidator;
use \Modules\News\Components\NewsComponent,
	\Modules\News\Models\News,
	\Modules\News\Models\NewsSubscribers;

// Framework
use \A,
	\CAuth,
	\CController,
	\CConfig,
	\CHash,
	\CDatabase,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\Languages,
	\ModulesSettings;

class NewsSubscribersController extends CController
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
        if(!Modules::model()->isInstalled('news')){	
            if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
            }else{
                $this->redirect(Website::getDefaultPage());
            }
        }
        Website::setMetaTags(array('title' => A::t('news', 'Subscribers')));
        $this->_view->actionMessage = '';
        $this->_view->errorField    = '';
		$this->_view->backendPath = $this->_backendPath;

        $this->_view->typeFirstName = ModulesSettings::model()->param('news', 'news_subscribers_first_name');
        $this->_view->typeLastName  = ModulesSettings::model()->param('news', 'news_subscribers_last_name');
        $this->_view->typeFullName  = ModulesSettings::model()->param('news', 'news_subscribers_full_name');

        $this->_view->title     = '';
        $this->_view->email     = '';
        $this->_view->firstName = '';
        $this->_view->lastName  = '';
        $this->_view->fullName  = '';
        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;

        // Fetch site settings info
        $this->_view->tabs = NewsComponent::prepareTab('subscribers');
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        if(CAuth::isLoggedInAsAdmin()){
            $action = 'manage';
        }else{
            $action = 'subscribe';
        }
        $this->redirect('newsSubscribers/'.$action);
    }
    
    /**
     * Manage subscription action handler
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'news', 'newsSubscribers/manage', false);
        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');
        
        if($alert){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
        $this->_view->render('newsSubscribers/manage');
    }
    
    /**
     * Add news action handler
     * @return void
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'news', 'newsSubscribers/manage', false);
        $this->_view->created_at = LocalTime::currentDateTime();
        $this->_view->render('newsSubscribers/add');
    }

    /**
     * Subscribe edit action handler
     * @return void
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'news', 'newsSubscribers/manage', false);
        
        $subscribe = $this->_checkSubscribeAccess($id);
        $this->_view->id = $subscribe->id;

        $this->_view->render('newsSubscribers/edit');
    }

    /**
     * Delete subscribe action handler
     * @param int $id the subscribe id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'news', 'newsSubscribers/manage', false);
        $subscribe = $this->_checkSubscribeAccess($id);

        if($subscribe->delete()){
            $alert     = A::t('news', 'Subscriber successfully deleted');
            $alertType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $alert     = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert     = A::t('news', 'Error remove subscriber');
                $alertType = 'error';
            }
        }
		
        if(!empty($alert)){
			A::app()->getSession()->setFlash('alert', $alert);
			A::app()->getSession()->setFlash('alertType', $alertType);
        }
		
        $this->redirect('newsSubscribers/manage');
    }

    /*
     * Adds user subscription and send message email
     * @param string $email
     * @return void
     */
    public function subscribeAction($email = '')
    {
        Website::setFrontend();
        $alertType = '';
        $alert = '';
		$errorField = '';
		$errors = array();
        
        if($email){
            $email = CHash::decrypt(urldecode($email), CConfig::get('password.hashKey'));
            if(CValidator::isEmail($email)){
               $this->_view->email = $email;
            }else{
                $alertType = 'validation';
                $alert = A::t('news', 'Input incorrect parameters');
            }
        }
        
        $cRequest = A::app()->getRequest();
        if('send' == $cRequest->getPost('act') && !$alert){
            $this->_view->firstName = $cRequest->getPost('first_name');
            $this->_view->lastName = $cRequest->getPost('last_name');
            $this->_view->fullName = trim($cRequest->getPost('full_name'));
            $this->_view->email = $cRequest->getPost('email');

			$validationFields = array();
            $validationFields['fields']['email'] = array('title' => A::t('news', 'Email'), 'validation' => array('type' => 'email', 'required' => true, 'maxLength' => 128));
            if($this->_view->typeFirstName != 'no') $validationFields['fields']['first_name'] = array('title'=>A::t('news', 'First Name'), 'validation' => array('type' => 'text', 'required' => $this->_view->typeFullName == 'no' && 'allow-required' == $this->_view->typeFirstName ? true : false, 'maxLength' => 32));
            if($this->_view->typeLastName != 'no') $validationFields['fields']['last_name'] = array('title'=>A::t('news', 'Last Name'), 'validation' => array('type' => 'text', 'required' => $this->_view->typeFullName == 'no' && 'allow-required' == $this->_view->typeLastName ? true : false, 'maxLength' => 32));
            if($this->_view->typeFullName != 'no') $validationFields['fields']['full_name'] = array('title'=>A::t('news', 'Full Name'), 'validation' => array('type' => 'text', 'required' => 'allow-required' == $this->_view->typeFullName ? true : false, 'maxLength' => 64));

			$result = CWidget::create('CFormValidation', $validationFields);			
            if($result['error']){
                $alertType = 'validation';
                $alert = $result['errorMessage'];
				$errorField = $result['errorField'];
            }else{
                $condition = 'email = :email';
                $params = array('s:email' => $this->_view->email);
                $subscribers = new NewsSubscribers();

                if($subscribers->find($condition, $params)){
                    $alertType = 'validation';
                    $alert = A::t('news', 'Exists Email');
                }else{
                    if('no' != $this->_view->typeFullName && $this->_view->fullName){
                        $posSpace = strpos($this->_view->fullName, ' ');
                        if($posSpace){
                            $this->_view->firstName = substr($this->_view->fullName, 0, $posSpace);
                            $this->_view->lastName = substr($this->_view->fullName, $posSpace+1);
                            if(!CValidator::validateMaxLength($this->_view->firstName, 32)){
                                $alertType = 'validation';
                                $alert = A::t('core', 'The {title} field length may be {max_length} characters maximum! Please re-enter.', array('{title}'=>A::t('news', 'First Name'), '{max_length}'=>'32'));
                            }elseif(!CValidator::validateMaxLength($this->_view->lastName, 32)){
                                $alertType = 'validation';
                                $alert = A::t('core', 'The {title} field length may be {max_length} characters maximum! Please re-enter.', array('{title}'=>A::t('news', 'Last Name'), '{max_length}'=>'32'));
                            }
                        //}else{
                        //    $alertType = 'validation';
                        //    $alert = A::t('core', 'The field {title} cannot be empty! Please re-enter.', array('{title}'=>A::t('news', 'Last Name')));
                        }
                    }elseif('no' == $this->_view->typeFullName){
                        $this->_view->fullName = ($this->_view->firstName ? $this->_view->firstName.' ' : '').$this->_view->lastName;
                    }
                    
                    if(empty($alert)){						
						$isBanned = false;						
						// Check if access is blocked to this IP address
						if(Website::checkBan('ip_address', $cRequest->getUserHostAddress(), $errors)){
							$isBanned = true;
						}else{
							// Check if access is blocked to this email
							if(Website::checkBan('email_address', $this->_view->email, $errors)){
								$isBanned = true;
							}else{
								// Check if access is blocked to this email domain
								if(Website::checkBan('email_domain', $this->_view->email, $errors)){
									$isBanned = true;
								}
							}
						}

						if($isBanned){
							$alertType = $errors['alertType'];
							$alert = $errors['alert'];
						}else{
							$subscribers->first_name = $this->_view->firstName;
							$subscribers->last_name = $this->_view->lastName;
							$subscribers->full_name = $this->_view->fullName;
							$subscribers->email = $this->_view->email;
							$subscribers->created_at = LocalTime::currentDateTime();
	
							if($subscribers->save()){
								$alertType = 'success';
								$unsubscribeUrl = A::app()->getRequest()->getBaseUrl().'newsSubscribers/unsubscribe/email/'.urlencode(CHash::encrypt($this->_view->email, CConfig::get('password.hashKey')));
								$params = array(
									'{FIRST_NAME}' => $this->_view->firstName,
									'{LAST_NAME}' => $this->_view->lastName,
									'{UNSUBSCRIBE_URL}' => $unsubscribeUrl
								);
								$result = Website::sendEmailByTemplate($this->_view->email, 'news_subscription', '', $params);
								
								$this->_view->firstName = '';
								$this->_view->lastName = '';
								$this->_view->fullName = '';
								$this->_view->email = '';
								if($result){
									$alert = A::t('news', 'You have subscribed');
								}else{
									$alert = A::t('news', 'You have successfully subscribed');
								}
							}else{
								if(APPHP_MODE == 'demo'){
									$alert     = CDatabase::init()->getErrorMessage();
									$alertType = 'warning';
								}else{
									$alert     = A::t('news', 'News new record error');
									$alertType = 'error';
								}
							}
						}
                    }
                }
            }
        }		
	
		$this->_view->errorField = $errorField;
        if($alert){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
		
        $this->_view->render('newsSubscribers/subscribe');
    }

    /*
     * Deletes user subscription and sends email
     * @param string $email
     * @return void
     */
    public function unsubscribeAction($email = 0)
    {
        Website::setFrontend();
        $alert = '';
        $alertType = '';
		$errorField = '';
        
        $cRequest = A::app()->getRequest();
        if('send' == $cRequest->getPost('act')){
            $result = CWidget::create('CFormValidation', array(
                'fields'=>array(
                    'email' => array(
                        'title' => A::t('news', 'Email'),
                        'validation' => array('required' => true, 'type' =>'email', 'maxLength' => 128)
                    ),
                ),
            ));

            $this->_view->email = $cRequest->getPost('email');
            $this->_view->title = A::t('news', 'Unsubscribing from News');
            
            if($result['error']){
                $alertType = 'validation';
                $alert = $result['errorMessage'];
                $errorField = $result['errorField'];
            }else{
                $condition = 'email = :email';
                $params    = array('s:email' => $this->_view->email);

                $subscribe = NewsSubscribers::model()->find($condition, $params);

                if(!$subscribe){
                    $alertType = 'error';
                    $alert = A::t('news', 'The specified email address not exist in our mailing list');
					$errorField = 'news_unsubscribe_email';
                }else{                    
                    if($subscribe->delete()){
                        $alertType = 'success';
                        $alert = A::t('news', 'You have been successfully unsubscribed');
                        $subscribeUrl = A::app()->getRequest()->getBaseUrl().'newsSubscribers/subscribe/email/'.urlencode(CHash::encrypt($this->_view->email, CConfig::get('password.hashKey')));
                        $params = array(
                            '{FIRST_NAME}'    => $subscribe->first_name,
                            '{LAST_NAME}'     => $subscribe->last_name,
                            '{SUBSCRIBE_URL}' => $subscribeUrl
                            );
                        // Email send
                        if(Website::sendEmailByTemplate($this->_view->email, 'news_unsubscription', '', $params)){
                            $alert .= ' '.A::t('news', 'You will receive a letter by email');
                        }
                        $this->_view->email = '';
                    }else{
                        if(APPHP_MODE == 'demo'){
                            $alertType = 'warning';
                            $alert     = CDatabase::init()->getErrorMessage();
                        }else{
                            $alertType = 'error';
                            $alert     = A::t('news', 'Error removing subscription');
                        }
                    }
                }
            }
        }else{
            $this->_view->title = A::t('news', 'Unsubscribing from News');
            if($email){
                $email = CHash::decrypt(urldecode($email), CConfig::get('password.hashKey'));
                if(CValidator::isEmail($email)){
                    $this->_view->email = $email;
                }else{
                    $alertType = 'validation';
                    $alert     = A::t('news', 'Input incorrect parameters');
                }
            }
        }
		
		$this->_view->errorField = $errorField;
        if($alert){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
        }
        $this->_view->render('newsSubscribers/unsubscribe');
    }

    /**
     * Check if passed subscription ID is valid
     * @param int $subscriberId
     * @return NewsSubscribers
     */
    private function _checkSubscribeAccess($subscriberId = 0)
    {
        $subscriber = NewsSubscribers::model()->findByPk((int)$subscriberId);
        if(!$subscriber){
            $this->redirect('newsSubscribers/manage');
        }
        return $subscriber;
    }

}
