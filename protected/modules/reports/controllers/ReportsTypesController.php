<?php
/**
 * ReportsTypes controller
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
	\Modules\Reports\Models\ReportsEntities;

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

class ReportsTypesController extends CController
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

        if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active Reports
            Website::setMetaTags(array('title'=>A::t('reports', 'Report Types Management')));
			// Set backend mode
			Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->backendPath = $this->_backendPath;

            $this->_cSession = A::app()->getSession();
            
            $this->_view->tabs = ReportsProjectsComponent::prepareTab('reportstypestab');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('reportsTypes/manage');
    }
    
    /**
     * Manage action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsTypes/manage', false);

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_view->render('reportsTypes/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'reports', 'reportsTypes/manage', false);

        $this->_view->render('reportsTypes/add');
    }

    /**
     * Edit Reports action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsTypes/manage', false);
        $reportsType = $this->_checkActionAccess($id);

        $this->_view->reportsType = $reportsType;
        $this->_view->render('reportsTypes/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'reports', 'reportsTypes/manage', false);
        $model = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';		

        if($model->delete()){
            if($model->getError()){
                $alert = A::t('reports', 'Report type cannot be deleted!');
                $alertType = 'warning';
            }else{
                $alert = A::t('reports', 'Report type has been successfully deleted!');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $alertType = 'warning';
            }else{
				$alert = $colors->getError() ? $colors->getErrorMessage() : A::t('reports', 'Report type cannot be deleted!');
                $alertType = 'error';
            }
        }
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsTypes/manage');
    }

    /**
     * Change report type state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsTypes/manage', false);

        $reportTypes = $this->_checkActionAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			$changeResult = ReportsTypes::model()->updateByPk($id, array('is_active' => ':is_active'), '', array(':is_active' => ($reportTypes->is_active == 1 ? '0' : '1')));
			if($changeResult){
				$alert = A::t('reports', 'Report type status has been changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('reports', 'Report type status cannot be changed!');
				$alertType = 'error';
			}
		}

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsTypes/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = ReportsTypes::model()->findByPk($id);
        if(!$model){
            $this->_cSession->setFlash('msgChange', A::t('reports', 'Wrong Report Type parameter!'));
            $this->_cSession->setFlash('msgChangeType', 'warning');
            $this->redirect('reportsTypes/manage');
        }
        return $model;
    }    
 
}