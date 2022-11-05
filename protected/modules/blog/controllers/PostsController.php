<?php
/**
 * Posts controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------				
 * __construct              _getShortcodes
 * indexAction              _replaceShortcodes
 * viewAllAction			_checkPostAccess
 * viewAction
 * manageAction
 * addAction
 * insertAction
 * editAction
 * updateAction
 * changeStatusAction
 * deleteAction
 * 
 */

namespace Modules\Blog\Controllers;

// Modules
use \Modules\Blog\Components\BlogComponent,
	\Modules\Blog\Models\Posts;

// Framework
use \A,
	\CAuth,
	\CController,
	\CDatabase,
	\CFile,
	\CLocale,
	\CTime,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\ModulesSettings;


class PostsController extends CController
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
		if(!Modules::model()->isInstalled('blog')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
		}

		// Fetch site settings info
    	$this->_settings = Bootstrap::init()->getSettings();
    	$this->_view->dateTimeFormat = $this->_settings->datetime_format;
        $this->_view->actionMessage = '';
        $this->_view->errorField = '';

		if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active language
            Website::setMetaTags(array('title'=>A::t('blog', 'Posts Management')));

            $this->_cRequest = A::app()->getRequest();
            $this->_cSession = A::app()->getSession();

			$this->_view->tabs = BlogComponent::prepareTab('blogposts');
			$this->_view->backendPath = $this->_backendPath;
		}
    }


	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
        if(CAuth::isLoggedInAsAdmin()){
            $this->redirect('posts/manage');    
        }else{
            $this->redirect('posts/viewAll');    
        }		
    }	
    
	/**
	 * Controller view all action handler
	 */
    public function viewAllAction()
	{
        // Set frontend mode
        Website::setFrontend();

		$alert = '';
		$alertType = '';
		$limit = 5;

		// Prepare pagination vars
		$this->_view->currentPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
		if($this->_view->currentPage <= 0) $this->_view->currentPage = 1;
		
        $this->_view->pageSize = ModulesSettings::model()->param('blog', 'posts_per_page');
		$this->_view->totalPosts = Posts::model()->count('publish_status = 1');
        $this->_view->postLength = ModulesSettings::model()->param('blog', 'post_preview_length');
	
		$this->_view->posts = Posts::model()->findAll(array(
			'condition'=>'publish_status = 1',
            'limit'=>(($this->_view->currentPage - 1) * $this->_view->pageSize).', '.$this->_view->pageSize,
			'order'=>'created_at DESC'
		));
		
        $this->_view->popularPosts = Posts::model()->findAll(array('condition'=>'publish_status = 1', 'limit'=>$limit, 'orderBy'=>'views DESC'));

		if(!$this->_view->totalPosts){
			$alert = A::t('blog', 'No posts yet');
			$alertType = 'warning';            
		}elseif(!count($this->_view->posts)){
			$alert = A::t('blog', 'Wrong parameter passed! Please try again later.');
            $alertType = 'error';
		}		

   		$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert));
		$this->_view->render('posts/viewAll');        
    }	

	/**
	 * Controller view post description
	 * @param int $pid
	 */
    public function viewAction($pid = 0)
	{
        // Set frontend mode
        Website::setFrontend();
        
        $limit = 5;
		$post = Posts::model()->findByPk((int)$pid, 'publish_status = 1');
		if($post){
            if(!A::app()->getSession()->isExists('blog_view_'.$pid)){
                $post->views++;
                $post->save();
                A::app()->getSession()->set('blog_view_'.$pid, 1);
            }

			$this->_view->postHeader = $post->post_header;
			$this->_view->postText = $post->post_text;
            $this->_view->createdBy = $post->created_by;
			$this->_view->createdAt = CLocale::date($this->_view->dateTimeFormat, $post->created_at);
            $this->_view->modifiedAt = ! CTime::isEmptyDateTime($post->modified_at) ? CLocale::date($this->_view->dateTimeFormat, $post->created_at) : '';
            $this->_view->popularPosts = Posts::model()->findAll(array('condition'=>'publish_status = 1', 'limit'=>$limit, 'orderBy'=>'views DESC'));
			$this->_view->views = $post->views;

			// Draw short codes of modules
			$this->_replaceShortcodes();

			$this->_view->render('posts/view');
		}else{
			$this->redirect('error/index');
		}
    }
    
    /**
     * Manage posts action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'posts', 'posts/manage', false);
		
        $actionMessage = '';

        $alert = $this->_cSession->getFlash('alert');
        $alertType = $this->_cSession->getFlash('alertType');

        if(!empty($alert)){
            $actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_view->actionMessage = $actionMessage;

        $this->_view->render('posts/manage');        
    }

    /**
     * Add post action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'posts', 'posts/manage', false);

        $this->_view->postHeader = '';
        $this->_view->postText = '';
        $this->_view->publishStatus = 1;
		$this->_view->shortCodes = $this->_getShortcodes();

        $this->_view->render('posts/add');
    }

    /**
     * Insert new post action handler
     */
    public function insertAction()
    {
        Website::prepareBackendAction('insert', 'posts', 'posts/manage', false);

        $this->_cRequest->getCsrfTokenValue();
		$this->_view->shortCodes = $this->_getShortcodes();		
        
    	if($this->_cRequest->getPost('act') == 'send'){            
            $alert = '';
            $alertType = '';
            
    		// Add post form submit
 	        $this->_view->postHeader = $this->_cRequest->getPost('post_header');
	        $this->_view->postText = $this->_cRequest->getPost('post_text');
	        $this->_view->publishStatus = $this->_cRequest->getPost('publish_status');
    		$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'post_header' =>array('title'=>A::t('blog', 'Post Header'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255)),
					'post_text'	  =>array('title'=>A::t('blog', 'Post Text'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>10000)),
				),
    		));
    		if($result['error']){
    			$alert = $result['errorMessage'];
    			$alertType = 'validation';    
    			$this->_view->errorField = $result['errorField'];
    		}else{
                $post = new Posts();
                $post->post_header = $this->_view->postHeader;
                $post->post_text = $this->_view->postText;
                $post->publish_status = $this->_view->publishStatus;
                $post->created_by = CAuth::getLoggedName();
                $post->created_at = LocalTime::currentDateTime();

   				if($post->save()){
                    $alert = A::t('blog', 'New Post Success Message');
                    $alertType = 'success';	    					
   					$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
   					$this->_view->render('posts/manage');
   					return;   
   				}else{
                    if(APPHP_MODE == 'demo'){
                        $alert = CDatabase::init()->getErrorMessage();
                        $alertType = 'warning';
                    }else{
                        $alert = A::t('blog', 'New Post Error Message');
                        $alertType = 'error';
                    }
                }
    		}
    		if(!empty($alert)){
    			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
    		}
    		$this->_view->render('posts/add');
    	}else{
    		$this->redirect('posts/manage');
    	}
    }

    /**
     * Post edit action handler
     * @param int $id the post id
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'posts', 'posts/manage', false);

		// Check access
		$post = $this->_checkPostAccess((int)$id);

    	$this->_cRequest = A::app()->getRequest();
        if($this->_cRequest->getPost('act') == 'send'){
    		$this->_view->language = $this->_cRequest->getPost('language'); 
    		$id = $this->_cRequest->getPost('id'); 
    	}else{
	        $this->_view->language = A::app()->getLanguage();
       	}
		
		$this->_view->shortCodes = $this->_getShortcodes();		
		$this->_view->post = $post;	
    	$this->_view->render('posts/edit');
    }

    /**
     * Update post action handler
     */
    public function updateAction()
    {
        Website::prepareBackendAction('update', 'posts', 'posts/manage', false);

		$this->_view->shortCodes = $this->_getShortcodes();
		
    	if($this->_cRequest->getPost('act') == 'send'){
            $alert = '';
            $alertType = '';

			// Check access
			$post = $this->_checkPostAccess($this->_cRequest->getPost('id', 'int'));
			
    		// Edit post form submit
 	        $this->_view->postHeader = $this->_cRequest->getPost('post_header');
	        $this->_view->postText = $this->_cRequest->getPost('post_text');

            $post->post_header = $this->_view->postHeader;
            $post->post_text = $this->_view->postText;
	        $post->publish_status = $this->_cRequest->getPost('publish_status');
    		$this->_view->post = $post;
    
    		$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'post_header' =>array('title'=>A::t('blog', 'Post Header'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255)),
					'post_text'	  =>array('title'=>A::t('blog', 'Post Text'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>10000)),
				),
    		));
    		if($result['error']){
    			$alert = $result['errorMessage'];
    			$alertType = 'validation';    
    			$this->_view->errorField = $result['errorField'];
    		}else{
    			// Update modified_at time to current time
    			$post->modified_at = LocalTime::currentDateTime();
    			
				if($post->save()){
					$alert = A::t('blog', 'Post Update Success Message');
					$alertType = 'success';	    					
					$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
                    if($this->_cRequest->isPostExists('btnUpdateClose')){
						$this->_cSession->setFlash('alert', $alert);
						$this->_cSession->setFlash('alertType', $alertType);
                        $this->redirect('posts/manage');
                        return;
                    }   
				}else{
					$this->_view->errorField = '';
					if(APPHP_MODE == 'demo'){
						$alert = CDatabase::init()->getErrorMessage();
						$alertType = 'warning';
					}else{
						$alert = A::t('blog', 'Post Update Error Message');
						$alertType = 'error';
					}
    			}
            }
    		if(!empty($alert)){
    			$this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
    		}    			
    		$this->_view->render('posts/edit');    			
    	}else{
    		$this->redirect('posts/manage');
    	}
    }

	/**
     * Change post state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id, $page = 1)
    {
        Website::prepareBackendAction('update', 'posts', 'posts/manage', false);

		// Check access
		$post = $this->_checkPostAccess((int)$id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			if(Posts::model()->updateByPk($id, array('publish_status'=>($post->publish_status == 1 ? '0' : '1')))){
				$alert = A::t('blog', 'Post status has been successfully changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('blog', 'An error occurred while changing post status! Please try again later.');
				$alertType = 'error';
			}
		}

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);
     
        $this->redirect('posts/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }
	
    /**
     * Delete post action handler
     * @param int $id the post id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'posts', 'posts/manage', false);
		
		// Check access
		$post = $this->_checkPostAccess((int)$id);
		
        // Check if default
        if($post->delete()){
            if($post->getError()){
                $alert = $post->getErrorMessage();
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
                $alert = $post->getError() ? $post->getErrorMessage() : A::t('blog', 'Post Delete Error Message');
                $alertType = 'error';
            }
        }

        $this->_cSession->setFlash('alert', $alert);
        $this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('posts/manage');
    }
    
    /**
     * Returns all available shortcodes
	 * @return array
     */
    private function _getShortcodes()
    {
		$shortCodes = array();
		$modulesCodes = ModulesSettings::model()->getShortcodes();
		if(is_array($modulesCodes)){
			foreach($modulesCodes as $key => $val){
				if($key == 'blog') continue;
				foreach($val as $vVal){
					$shortCodes[] = array('value'=>$vVal['value'], 'description'=>$vVal['description']);
				}
			}
		}

		return $shortCodes;
	}    
  
	/**
	 * Replace content with shortcodes
	 * @return void
	 */
	private function _replaceShortcodes()
	{
		$modulesCodes = ModulesSettings::model()->getShortcodes();
		if(is_array($modulesCodes)){
			$moduleText = $this->_view->postText;
			foreach($modulesCodes as $key => $val){
				if ($key == 'blog') continue;
				foreach ($val as $vVal) {
					$matches = array();
					$pattern = str_replace(array('|', '|ID', '|opened'), array('\\|', '|([0-9]+?)', '|(.*?)'), $vVal['value']);
					if (preg_match_all('/' . $pattern . '/i', $moduleText, $matches)) {
						$componentName = A::app()->mapAppModuleClass((!empty($vVal['class_code']) ? $vVal['class_code'] : $key) . 'Component');
						if (empty($componentName)) {
							$componentName = ucfirst($key . 'Component');
						}
						$countMatches = count($matches);
						for ($ind = 0; $ind < $countMatches; $ind++) {
							$params = array();
							if (method_exists($componentName, 'drawShortcode')) {
								$pattern = !empty($matches) ? str_replace('|', '\\|', $matches[0][$ind]) : $vVal['value'];
								if (!empty($pattern)) {
									$pattern = '/' . $pattern . '/i';
									// Prepare params
									if (isset($matches[1][$ind])) $params[] = $matches[1][$ind];
									if (isset($matches[2][$ind])) $params[] = $matches[2][$ind];
									$moduleText = preg_replace($pattern, call_user_func_array($componentName . '::drawShortcode', array($params)), $moduleText, 1);
								}
							} else {
								CDebug::addMessage('errors', 'component-error', A::t('core', 'Component or method does not exist: {component}', array('{component}' => $componentName . '::drawShortcode()')));
							}
						}
					}
				}
			}
			$this->_view->postText = $moduleText;
		}
	}

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return obj
     */
    private function _checkPostAccess($id = 0)
    {
        $post = Posts::model()->findByPk($id);
        if(!$post){
            $this->redirect('posts/manage');
        }

        return $post;
    }

}

