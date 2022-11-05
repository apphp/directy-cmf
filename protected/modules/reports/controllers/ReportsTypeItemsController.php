<?php
/**
 * ReportsTypesItems controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkActionAccess
 * indexAction              
 * manageAction
 * addAction
 * editAction
 * deleteAction
 * changeStatusAction
 *
 */

namespace Modules\Reports\Controllers;

// Modules
use \Modules\Reports\Components\ReportsProjectsComponent,
	\Modules\Reports\Models\ReportsTypes,
	\Modules\Reports\Models\ReportsEntities,
	\Modules\Reports\Models\ReportsTypeItems;

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

class ReportsTypeItemsController extends CController
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
        if(!Modules::model()->isInstalled('reports')){
			if(CAuth::isLoggedInAsAdmin()){
				$this->redirect($this->_backendPath.'modules/index');
			}else{
				$this->redirect(Website::getDefaultPage());
			}
        }

        $this->_view->fieldTypes = array(
			'autoIncrement'		=> 'auto increment',
            'textbox' 			=> 'textbox',
            'textarea' 			=> 'textarea',
            'imageUpload' 		=> 'image',
            'datetime' 			=> 'date',
            'videoLink' 		=> 'video link',
            'fileUpload' 		=> 'file'
        );

        $this->_view->validationTypes = array(
            '' 				=> '',
            'alpha' 		=> 'alphabetic',
            'numeric' 		=> 'numeric',
            'alphanumeric' 	=> 'alphanumeric',
            'phone' 		=> 'phone',
            'phoneString' 	=> 'phone (string)',
            'mixed' 		=> 'mixed',
            'email'	 		=> 'email',
            'date' 			=> 'date',
			'integer'		=> 'integer',
            'positiveInteger' => 'integer (positive)',
			'percent' 		=> 'percent',
            'float' 		=> 'float',
            'url' 			=> 'url',
            'ip' 			=>'ip',
            //'set' 			=> 'set',
            'text' 			=> 'text'
        );
		
        $this->_cSession = A::app()->getSession();
        $this->_cRequest = A::app()->getRequest();

        if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active Reports
            Website::setMetaTags(array('title'=>A::t('reports', 'Report Type Items Management')));
			// Set backend mode
			Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->backendPath = $this->_backendPath;

            $this->_view->tabs = ReportsProjectsComponent::prepareTab('reportstypestab');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('reportsTypeItems/manage');
    }
    
    /**
     * Manage action handler
     * @param int $reportType
     */
    public function manageAction($reportType = 0)
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsTypeItems/manage', false);

        $this->_checkAccessPage($reportType);

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_view->render('reportsTypeItems/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'reports', 'reportsTypeItems/manage', false);
        $this->_view->reportTypeId = $this->_cRequest->getQuery('reportType', '','');

        $this->_checkAccessPage($this->_view->reportTypeId);

        $reportsType = ReportsTypes::model()->findByPk($this->_view->reportTypeId);
        if(isset($reportsType)){
            $itemsCount = ReportsTypeItems::model()->count('type_id = :type_id', array(':type_id' => $this->_view->reportTypeId));
            if($itemsCount >= 20){
                $this->_cSession->setFlash('msgChange', A::t('reports', 'You have reached the maximum allowed number of fields for this report (20 fields)!'));
                $this->_cSession->setFlash('msgChangeType', 'warning');
                $this->redirect('reportsTypeItems/manage/reportType/'.$this->_view->reportTypeId);
            }else{
                $this->_view->reportTypeName = $reportsType->name;
                $this->_view->render('reportsTypeItems/add');
            }
        }else{
            $this->_view->render('error/404');
        }
    }

    /**
     * Edit Reports action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsTypeItems/manage', false);
        $reportsTypeItem = $this->_checkActionAccess($id);

        $this->_checkAccessPage($id);

        $alert = '';
        $alertType = '';

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button' => true)));
        }
        $this->_view->reportTypeId = $this->_cRequest->getQuery('reportType', '','');
        $reportsType = ReportsTypes::model()->findByPk($this->_view->reportTypeId);
        if(isset($reportsType)){
            $this->_view->reportTypeName = $reportsType->name;
        }else{
            $this->_view->render('error/404');
        }
        $this->_view->reportsTypeItem = $reportsTypeItem;
        $this->_view->render('reportsTypeItems/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'reports', 'reportsTypeItems/manage', false);
        $this->_checkAccessPage($id);
        $model = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';		

        if($model->delete()){
            if($model->getError()){
                $alert = A::t('reports', 'Report Type Item cannot be deleted!');
                $alertType = 'warning';
            }else{
                $alert = A::t('reports', 'Report Type Item has been successfully deleted!');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $alertType = 'warning';
            }else{
				$alert = $model->getError() ? $model->getErrorMessage() : A::t('reports', 'Report type item cannot be deleted!');
                $alertType = 'error';
            }
        }

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsTypeItems/manage/reportType/'.$id);
    }

    /**
     * Change report type item state method
     * @param string $type 
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($type = '', $id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsTypeItems/manage', false);
		
        $reportTypeItem = $this->_checkActionAccess($id);
		if(!in_array($type, array('required', 'active', 'show', 'internal'))){
			$this->redirect('reportsTypeItems/manage/reportType/'.$reportTypeItem->type_id);
		}

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			switch($type){
				case 'required':
					$changeResult = ReportsTypeItems::model()->updateByPk($id, array('field_required' => ':field_required'), '', array(':field_required' => ($reportTypeItem->field_required == 1 ? '0' : '1')));
					break;
				case 'show':
					$changeResult = ReportsTypeItems::model()->updateByPk($id, array('show_on_mainview' => ':show_on_mainview'), '', array(':show_on_mainview' => ($reportTypeItem->show_on_mainview == 1 ? '0' : '1')));
					break;
				case 'internal':
					$changeResult = ReportsTypeItems::model()->updateByPk($id, array('internal_use' => ':internal_use'), '', array(':internal_use' => ($reportTypeItem->internal_use == 1 ? '0' : '1')));
					break;
				default:
					$changeResult = ReportsTypeItems::model()->updateByPk($id, array('is_active' => ':is_active'), '', array(':is_active' => ($reportTypeItem->is_active == 1 ? '0' : '1')));
					break;
			}

			if($changeResult){
				$alert = A::t('reports', 'Report Type Item status has been changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('reports', 'Report Type Item status cannot be changed');
				$alertType = 'error';
			}
		}		

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsTypeItems/manage/reportType/'.$reportTypeItem->type_id.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Check access to page with params
     * @param int $reportType
     */
    private function _checkAccessPage($reportType = 0)
    {
        $reportsTypes = ReportsTypes::model()->findByPk($reportType);
        if(!isset($reportsTypes)){
            $this->_view->reportType = $this->_cRequest->getQuery('reportType', '','');
            if($this->_view->reportType == ''){
                $this->_cSession->setFlash('msgChange', A::t('reports', 'Wrong Report Type parameter!'));
                $this->_cSession->setFlash('msgChangeType', 'warning');
                $this->redirect('reportsTypes/manage/');

            }
            $reportsTypes = ReportsTypes::model()->findByPk($this->_view->reportType);
            if(!isset($reportsTypes)){
                $this->_cSession->setFlash('msgChange', A::t('reports', 'Wrong Report Type parameter!'));
                $this->_cSession->setFlash('msgChangeType', 'warning');
                $this->redirect('reportsTypes/manage/');
            }

            $this->_view->reportTypeId = $reportsTypes->id;
            $this->_view->reportTypeName = $reportsTypes->name;
        }else{
            $this->_view->reportTypeId = $reportsTypes->id;
            $this->_view->reportTypeName = $reportsTypes->name;
        }

        //if(A::app()->getSession()->hasFlash('message')){
        //    A::app()->getSession()->getFlash('message');
        //}
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = ReportsTypeItems::model()->findByPk($id);
        $reportType = $this->_cRequest->getQuery('reportType', '','');
        $reportsTypes = ReportsTypes::model()->findByPk($this->_view->reportType);
        if(!$model){
            if(!$reportsTypes){
                $this->redirect('reportsTypeItems/manage');
            }else{
                $this->redirect('reportsTypeItems/manage/reportType/'.$reportType);
            }
        }

        return $model;
    }    
 
}
