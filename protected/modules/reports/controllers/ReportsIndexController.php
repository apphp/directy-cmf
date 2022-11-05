<?php
/**
 * ReportsIndex controller
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct
 * indexAction
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

class ReportsIndexController extends CController
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
            Website::setMetaTags(array('title'=>A::t('reports', 'Index')));
			// Set backend mode
			Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->backendPath = $this->_backendPath;
		}
    }

    /**
     * Controller default action handler
     */
    public function indexAction($code = '404')
    {
        if(in_array($code, array('404', '500'))){
            $redirectCode = $code;
        }elseif(strtolower($code) == 'no-privileges'){
			$redirectCode = 'noprivileges';
        }else{
            $redirectCode = 'index';
        }

		// Display error description
		$this->_view->errorDescription = '';
		if(APPHP_MODE == 'debug'){
			$this->_view->errorDescription = A::app()->getSession()->getFlash('error500');
		}

        $this->_view->render('reportsindex/'.$redirectCode);
    }

}
