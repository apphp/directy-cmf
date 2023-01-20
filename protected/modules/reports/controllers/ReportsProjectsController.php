<?php
/**
 * ReportsProjects controller
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
 * viewAllAction
 *
 */

namespace Modules\Reports\Controllers;

// Modules
use \Modules\Reports\Components\ReportsProjectsComponent,
	\Modules\Reports\Models\ReportsTypes,
	\Modules\Reports\Models\ReportsEntities,
	\Modules\Reports\Models\ReportsProjects,
    \Modules\Reports\Models\ReportsEntityItems;

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
	\ModulesSettings,
	\Currencies;


class ReportsProjectsController extends CController
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
            Website::setMetaTags(array('title'=>A::t('reports', 'Projects Management')));
			// Set backend mode
			Website::setBackend();

            $this->_view->errorField = '';
            $this->_view->actionMessage = '';
            $this->_view->backendPath = $this->_backendPath;

            // Fetch site settings info
            $this->_settings = Bootstrap::init()->getSettings();
            $this->_view->dateTimeFormat = $this->_settings->datetime_format;
            $this->_view->dateFormat = $this->_settings->date_format;

			// Fetch currency settings info
			if($currency = Currencies::model()->getDefaultCurrencyInfo()){
				$this->_view->currencySymbol = $currency->symbol;
				$this->_view->currencyPlace = $currency->symbol_place;
			}else{
				$this->_view->currencySymbol = '';
				$this->_view->currencyPlace = 'before';    
			}        
			$this->_view->numberFormat = Bootstrap::init()->getSettings('number_format');

            $this->_cSession = A::app()->getSession();

            $this->_view->tabs = ReportsProjectsComponent::prepareTab('reportsprojectstab');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('reportsProjects/manage');
    }
    
    /**
     * Manage action handler
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsProjects/manage', false);

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_view->render('reportsProjects/manage');
    }

    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'reports', 'reportsProjects/manage', false);

        $this->_view->render('reportsProjects/add');
    }

    /**
     * Edit Reports action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsProjects/manage', false);
        $project = $this->_checkActionAccess($id);
        
        $this->_view->project = $project;
        $this->_view->render('reportsProjects/edit');
    }

    /**
     * Change project status
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsProjects/manage', false);
		
		$project = $this->_checkActionAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			$changeResult = ReportsProjects::model()->updateByPk($id, array('is_active'=>($project->is_active == 1 ? '0' : '1')));
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

        $this->redirect('reportsProjects/manage'.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'reports', 'reportsProjects/manage', false);
        $project = $this->_checkActionAccess($id);

        $alert = '';
        $alertType = '';		

        if($project->delete()){
            if($project->getError()){
                $alert = A::t('reports', 'Project cannot be deleted!');
                $alertType = 'warning';
            }else{
				// Prepare IDs of all reports for this project
				$reports = ReportsEntities::model()->findall('project_id = :id', array(':id' => $id));
				$reportIds = array();
				foreach($reports as $key => $val){
					$reportIds[] = $val['id'];
				}
				
				if(!empty($reportIds)){
					// Remove all reports related to to this project
					ReportsEntities::model()->deleteAll('project_id = :id', array(':id' => $id));
					// Remove all items related to to this project
					ReportsEntityItems::model()->deleteAll('entity_id IN ('.implode(',', $reportIds).')');
				}

                $alert = A::t('reports', 'Project has been successfully deleted!');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $alertType = 'warning';
            }else{
				$alert = $colors->getError() ? $colors->getErrorMessage() : A::t('reports', 'Project cannot be deleted!');
                $alertType = 'error';
            }
        }
		
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsProjects/manage');
    }

    /**
     * View the module on Frontend
     */
    public function viewAllAction()
    {
        // Set frontend mode
        Website::setFrontend();
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = ReportsProjects::model()->findByPk($id);
        if(!$model){
            $this->_cSession->setFlash('msgChange', A::t('reports', 'Wrong Project parameter!'));
            $this->_cSession->setFlash('msgChangeType', 'warning');
            $this->redirect('reportsProjects/manage');
        }
        return $model;
    }    
 
}