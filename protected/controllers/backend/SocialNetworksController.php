<?php
/**
 * SocialNetworks Controller controller
 *
 * PUBLIC:                  	PRIVATE:
 * ---------------          	---------------
 * __construct              	_checkSocialLoginAccess
 * indexAction					_prepareTab
 * manageAction
 * addAction
 * editAction
 * changeNetworkStatusAction
 * clearDataAction
 * deleteAction
 * loginAction
 * loginEditAction
 * changeLoginStatus
 *
 */

class SocialNetworksController extends CController
{
	private $_backendPath = '';

	/**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

		// Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

        // Set meta tags according to active language
        Website::setMetaTags(array('title'=>A::t('app', 'Social Settings')));
        // Set backend mode
        Website::setBackend();

		$this->_view->backendPath = $this->_backendPath;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect($this->_backendPath.'socialNetworks/manage');
    }

    /**
     * Social networks settings action handler
     * @return void
     */
    public function manageAction()
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'view')){
            $this->redirect($this->_backendPath.'dashboard/index');
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render($this->_backendPath.'socialNetworks/manage');
    }

    /**
     * Add new action handler
     * @param string $typeTab the type sub-tab ('approved', 'pending', 'expired' and 'all')
     * @return void
     */
    public function addAction()
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'edit')){
            $this->redirect($this->_backendPath.'socialNetworks/manage');
        }

        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render($this->_backendPath.'socialNetworks/add');
    }

    /**
     * Edit action handler
     * @param int $id
     * @param string $typeTab
     * @param string $delete the type image delete ('image')
     * @return void
     */
    public function editAction($id = 0, $delete = '')
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'edit')){
            $this->redirect($this->_backendPath.'socialNetworks/manage');
        }

        $socialNetwork = SocialNetworks::model()->findByPk($id);

        if(empty($socialNetwork)){
            $alert = A::t('app', 'Input incorrect parameters');
            $alertType = 'error';
            A::app()->getSession()->setFlash('alert', $alert);
            A::app()->getSession()->setFlash('alertType', $alertType);
            $this->redirect($this->_backendPath.'socialNetworks/manage');
        }

        // Delete the icon file
        if($delete == 'image'){
            $icon = $socialNetwork->icon;
            $socialNetwork->icon = '';
            $imagePath = 'images/social_networks/'.$icon;
                // Save the changes in admins table
                if($socialNetwork->save()){
                    // Delete the files
                    if(!empty($imagePath) && CFile::deleteFile($imagePath)){
                        $alert = A::t('app', 'Image successfully deleted');
                        $alertType = 'success';
                    }else{
                        $alert = A::t('app', 'Image Delete Warning');
                        $alertType = 'warning';
                    }
                }else{
                    $alert = A::t('app', 'Delete Error Message');
                    $alertType = 'error';
                }

            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        $this->_view->id = $id;
        $this->_view->tabs = $this->_prepareTab('social');
        $this->_view->render($this->_backendPath.'socialNetworks/edit');
    }

    /**
     * Change status social network action handler
     * @param int $id       the network ID
     * @param int $page 	the page number
     * @return void
     */
    public function changeNetworkStatusAction($id, $page = 0)
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'edit')){
            $this->redirect($this->_backendPath.'socialNetworks/manage');
        }

		$alert = '';
		$alertType = '';

        $socialNetwork = SocialNetworks::model()->findbyPk((int)$id);
        if(!empty($socialNetwork)){
			if(APPHP_MODE == 'demo'){
				$alert = A::t('app', 'This operation is blocked in Demo Mode!');
				$alertType = 'warning';
			}else{
				if(SocialNetworks::model()->updateByPk($socialNetwork->id, array('is_active'=>($socialNetwork->is_active == 1 ? '0' : '1')))){
					$alert = A::t('app', 'Status has been successfully changed!');
					$alertType = 'success';
				}else{
					$alert = A::t('app', 'Status changing error');
					$alertType = 'error';
				}
			}
        }

		if(!empty($alert) && !empty($alertType)){
			A::app()->getSession()->setFlash('alert', $alert);
			A::app()->getSession()->setFlash('alertType', $alertType);
		}

        $this->redirect($this->_backendPath.'socialNetworks/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }


	/**
	 * Delete social network action handler
	 * @param int $id       the network ID
	 * @return void
	 */
	public function clearDataAction($id)
	{
		// Block access if admin has no active privileges to access site settings
		if(!Admins::hasPrivilege('social_networks', 'edit')){
			$this->redirect($this->_backendPath.'socialNetworks/login');
		}
		$socialLogin = $this->_checkSocialLoginAccess($id);

		$socialLogin->is_active = 0;
		$socialLogin->application_id = '';
		$socialLogin->application_secret = '';

		// Save the changes in admins table
		if($socialLogin->save()){
			$alert = A::t('app', 'All data has been successfully deleted!');
			$alertType = 'success';
		}else{
			if(APPHP_MODE == 'demo'){
				$alert = A::t('app', 'This operation is blocked in Demo Mode!');
				$alertType = 'warning';
			}else{
				$alert = A::t('app', 'Delete Data Error Message');
				$alertType = 'error';
			}
		}

		if(!empty($alert) && !empty($alertType)){
			A::app()->getSession()->setFlash('alert', $alert);
			A::app()->getSession()->setFlash('alertType', $alertType);
		}

		$this->redirect($this->_backendPath.'socialNetworks/loginEdit/id/'.$id);
	}


    /**
     * Delete social network action handler
	 * @param int $id       the network ID
     * @return void
     */
    public function deleteAction($id)
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'delete')){
            $this->redirect($this->_backendPath.'dashboard/index');
        }

        $socialNetwork = SocialNetworks::model()->findByPk($id);

        if(!empty($socialNetwork)){
            $icon = $socialNetwork->icon;
            $imagePath = 'images/social_networks/'.$icon;
            if($socialNetwork->delete()){
                if($icon ? CFile::deleteFile($imagePath) : true){
                    $alert = A::t('app', 'Icon successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('app', 'Edit Social Network');
                    $alertType = 'warning';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('app', 'Social Network deleting error');
                    $alertType = 'error';
                }
            }
            $alert = A::app()->getSession()->setFlash('alert', $alert);
            $alertType = A::app()->getSession()->setFlash('alertType', $alertType);
        }

        $this->redirect($this->_backendPath.'socialNetworks/manage');
    }

    /**
     * Social Login action handler
     * @param string $typeTab
     * @return void
     */
    public function loginAction()
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'view')){
            $this->redirect($this->_backendPath.'dashboard/index');
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->tabs = $this->_prepareTab('login');
        $this->_view->render($this->_backendPath.'socialNetworks/login');
    }

    /**
     * Social Login Edit action handler
     * @param int $id
     * @return void
     */
    public function loginEditAction($id = 0, $image = '')
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'edit')){
            $this->redirect($this->_backendPath.'socialNetworks/login');
        }
        $socialLogin = $this->_checkSocialLoginAccess($id);

		$alert = A::app()->getSession()->getFlash('alert');
		$alertType = A::app()->getSession()->getFlash('alertType');

		if(!empty($alertType)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		}

		if($image == 'delete'){
            $iconPath = 'images/social_login/'.$socialLogin->button_image;
            $socialLogin->button_image = '';
			$alert = $alertType = '';

            // Save the changes in admins table
            if($socialLogin->save()){
                // Delete the files. If deleteThumb == true, then delete image thumb file
                if(CFile::deleteFile($iconPath)){
                    $alert = A::t('app', 'Icon successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('app', 'Image Delete Warning');
                    $alertType = 'warning';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = A::t('app', 'This operation is blocked in Demo Mode!');
                    $alertType = 'warning';
                }else{
                    $alert = A::t('app', 'Image Delete Error');
                    $alertType = 'error';
                }
            }

			if(!empty($alertType)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
			}
        }

        $this->_view->tabs = $this->_prepareTab('login');
        $this->_view->id = $socialLogin->id;
		$this->_view->type = $socialLogin->type;
		$this->_view->application_id = $socialLogin->application_id;
		$this->_view->application_secret = $socialLogin->application_secret;

		$this->_view->render($this->_backendPath.'socialNetworks/loginEdit');
    }

    /**
     * Change status social network action handler
     * @param int $id the login ID
     * @return void
     */
    public function changeLoginStatusAction($id = 0)
    {
        // Block access if admin has no active privileges to access site settings
        if(!Admins::hasPrivilege('social_networks', 'edit')){
            $this->redirect($this->_backendPath.'socialNetworks/login');
        }
        
		$alert = '';
		$alertType = '';

		$socialLogin = SocialNetworksLogin::model()->findbyPk((int)$id);
        if(!empty($socialLogin)){
			if(APPHP_MODE == 'demo'){
				$alert = A::t('app', 'This operation is blocked in Demo Mode!');
				$alertType = 'warning';
			}else{
				if($socialLogin->application_id == ''){
					$alert = A::t('app', 'Change of status is not possible due to the fact that the Application ID is empty!');
					$alertType = 'error';
				}elseif($socialLogin->application_id && SocialNetworksLogin::model()->updateByPk($socialLogin->id, array('is_active'=>($socialLogin->is_active == 1 ? '0' : '1')))){
					$alert = A::t('app', 'Status has been successfully changed!');
					$alertType = 'success';
				}else{
					$alert = A::t('app', 'Status changing error');
					$alertType = 'error';
				}			
			}
        }
		
		if(!empty($alert) && !empty($alertType)){
			A::app()->getSession()->setFlash('alert', $alert);
			A::app()->getSession()->setFlash('alertType', $alertType);
		}
		
        $this->redirect($this->_backendPath.'socialNetworks/login');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return object
     */
    private function _checkSocialLoginAccess($id = 0)
    {
        $model = SocialNetworksLogin::model()->findByPk($id);
        if(!$model){
            $this->redirect($this->_backendPath.'socialNetworks/login');
        }
        return $model;
    }

    /**
     * Prepare settings tabs
     * @param string $activeTab general|visual|local|email|templates|server|site|cron
     */
    private function _prepareTab($activeTab = 'social')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'=>array(),
            'contentMessage'=>'',
            'tabs'=>array(
                A::t('app', 'Social Networks') => array('href'=>$this->_backendPath.'socialNetworks/manage', 'id'=>'tab1', 'content'=>'', 'active'=>($activeTab == 'social' ? true : false)),
                A::t('app', 'Social Login') => array('href'=>$this->_backendPath.'socialNetworks/login', 'id'=>'tab2', 'content'=>'', 'active'=>($activeTab == 'login' ? true : false)),
            ),
            'return'=>true,
        ));
    }
}
