<?php
/**
 * ReportsEntities controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkActionAccess
 * indexAction              _checkAccessPage
 * manageAction				_preparePreview
 * changeStatusAction		_displayTabs
 * showCommentsAction
 * addCommentAction
 * editCommentAction
 * deleteCommentAction
 * changeCommentStatusAction
 * editReportAction
 * previewAction
 * getPdfAction
 * 
 */

namespace Modules\Reports\Controllers;

// Modules
use \Modules\Reports\Components\ReportsProjectsComponent,
	\Modules\Reports\Models\ReportsTypes,
	\Modules\Reports\Models\ReportsTypeItems,
	\Modules\Reports\Models\ReportsEntities,
	\Modules\Reports\Models\ReportsEntityItems,
	\Modules\Reports\Models\ReportsEntityComments,
	\Modules\Reports\Models\ReportsProjects;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CFile,
	\CDatabase,
	\CPdf,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\Languages,
	\ModulesSettings;


class ReportsEntitiesController extends CController
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
            Website::setMetaTags(array('title'=>A::t('reports', 'Reports Management')));
			// Set backend mode
			Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->backendPath = $this->_backendPath;

            $this->_cRequest = A::app()->getRequest();
            $this->_cSession = A::app()->getSession();

            // Fetch site settings info
            $this->_settings = Bootstrap::init()->getSettings();
            $this->_view->dateTimeFormat = $this->_settings->datetime_format;
            $this->_view->dateFormat = $this->_settings->date_format;
            $this->_view->format = $this->_settings->number_format;
            
            $this->_view->tabs = ReportsProjectsComponent::prepareTab('reportsprojectstab');
        }
	}

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('reportsEntities/manage');
    }
    
    /**
     * Manage action handler
     * @param int $projectId
     */
    public function manageAction($projectId = 0)
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsEntities/manage', false);

        $this->_checkAccessPage($projectId);

        // Get types data
        $typeData = ReportsTypes::model()->findAll();
        $countTypeData = count($typeData);
        $reportNameList = array();
        for($type = 0; $type < $countTypeData; $type++){
            $reportNameList[$typeData[$type]['id']] = $typeData[$type]['name'];
        }

        // Get entities data
        $relatedData = ReportsEntities::model()->findAll('project_id = :project_id', array(':project_id' => $projectId));
        $countRelatedData = count($relatedData);
        $entitiesList = array();
        for($rel = 0; $rel < $countRelatedData; $rel++){
            if(isset($reportNameList[$relatedData[$rel]['type_id']])) $entitiesList[$relatedData[$rel]['id']] = $reportNameList[$relatedData[$rel]['type_id']];
        }

        $this->_view->reportNameList = $entitiesList;

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');			
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		}

		$this->_displayTabs($projectId);
        $this->_view->render('reportsEntities/manage');
    }

    /**
     * Change report status
     * @param int $projectId
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($projectId = 0, $id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsProjects/manage', false);
		$reportsEntities = $this->_checkActionAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			$changeResult = ReportsEntities::model()->updateByPk($id, array('is_active'=>($reportsEntities->is_active == 1 ? '0' : '1')));
			if($changeResult){
				$alert = A::t('reports', 'Report status has been changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('reports', 'Report status cannot be changed!');
				$alertType = 'error';
			}
		}

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsEntities/manage/projectId/'.$projectId.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Show all comments for current project and current report
     * @param int $projectId
     * @param int $reportId
     */
    public function showCommentsAction($projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsEntities/showComments', false);
        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);

        $this->_displayTabs($projectId);

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');			
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
		}

        $this->_view->reportId = $reportId;
        $this->_view->render('reportsEntities/showComments');
    }

    /**
     * Add new comment to report
     * @param int $projectId
     * @param int $reportId
     *
     */
    public function addCommentAction($projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('add', 'reports', 'reportsEntities/addComment', false);
        $this->_view->projectId = $projectId;
        $this->_view->reportId = $reportId;

        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);

        $this->_displayTabs($projectId);

        $this->_view->render('reportsEntities/add');
    }

    /**
     * Edit comment
     * @param int $id
     * @param int $projectId
     * @param int $reportId
     */
    public function editCommentAction($id = 0, $projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsEntities/manage', false);

        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);
        $this->_displayTabs($projectId);

        $this->_view->reportsEntityComments = ReportsEntityComments::model()->findByPk($id);
        $reportsEntityComments = ReportsEntityComments::model()->findByPk($id);
        if(!isset($reportsEntityComments)){
            $this->_cSession->setFlash('alert', A::t('reports', 'Wrong Comment parameter!'));
            $this->_cSession->setFlash('alertType', 'warning');
            $this->redirect('reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId);
        }

        $this->_view->commentImage1 = 'image_1';
        $this->_view->commentImage2 = 'image_2';
        $this->_view->commentImage3 = 'image_3';
        $this->_view->commentImage4 = 'image_4';

        $image = $this->_cRequest->getQuery('image', '','');
        $imageFile = $this->_cRequest->getQuery('imgfile', '','');
		
		// delete the file
		$file = $this->_cRequest->getQuery('file', '','');
		
        if($file === 'delete'){
            $alert = '';
            $alertType = '';
            $file = 'assets/modules/reports/files/'.$reportsEntityComments->fileAttachment;
            $reportsEntityComments->fileAttachment = '';
            if($reportsEntityComments->save()){
                // delete the images
                if(CFile::deleteFile($file)){
                    $alert = A::t('reports', 'File has been successfully deleted!');
                    $alertType = 'success';
                }else{
                    $alert = A::t('reports', 'File cannot be deleted!');
                    $alertType = 'warning';
                }
            }else{
                $alert = A::t('reports', 'File cannot be deleted!');
                $alertType = 'error';
            }
            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }
		

        $reportsEntityComments->changed_date = LocalTime::currentDateTime();
        // Delete the image file
        if($image === 'delete'){
            $alert = '';
            $alertType = '';
            $image = 'assets/modules/reports/images/'.$reportsEntityComments->$imageFile;
            $reportsEntityComments->$imageFile = '';
            if($reportsEntityComments->save()){
                // Delete the images
                if(CFile::deleteFile($image)){
                    $alert = A::t('reports', 'Image has been successfully deleted!');
                    $alertType = 'success';
                }else{
                    $alert = A::t('reports', 'Image cannot be deleted!');
                    $alertType = 'warning';
                }
            }else{
                $alert = A::t('reports', 'Image cannot be deleted!');
                $alertType = 'error';
            }
            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        $this->_view->reportId = $reportId;
        $this->_view->render('reportsEntities/edit');
    }

    /**
     * Delete current comment
     * @param int $id
     * @param int $projectId
     * @param int $reportId
     */
    public function deleteCommentAction($id = 0, $projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('delete', 'reports', 'reportsEntities/deleteComment', false);

        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);
        // Check access for comment
        $comment = ReportsEntityComments::model()->findByPk($id);
        if(!$comment){
            $this->redirect('reportsEntities/showComments');
        }

        if($comment->delete()){
            if($comment->getError()){
                $alert = A::t('reports', 'Comment cannot be deleted!');
                $alertType = 'warning';
            }else{
                $alert = A::t('reports', 'Comment has been successfully deleted!');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('reports', 'Comment cannot be deleted!');
                $alertType = 'error';
            }
        }

        if(!empty($alert)){
			$this->_cSession->setFlash('alert', $alert);
			$this->_cSession->setFlash('alertType', $alertType);
        }

        $this->redirect('reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId);
    }

    /**
     * Change project status
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeCommentStatusAction($id = 0, $page = 1, $projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsEntities/showComments', false);

        $comment = ReportsEntityComments::model()->findByPk($id);

        if(ReportsEntityComments::model()->updateByPk($id, array('is_active'=>($comment->is_active == 1 ? '0' : '1'), 'changed_date' => LocalTime::currentDate()))){
            $this->_cSession->setFlash('alert', A::t('reports', 'Comment status has been successfully changed!'));
            $this->_cSession->setFlash('alertType', 'success');
        }else{
            $this->_cSession->setFlash('alert', 'Comment cannot be changed! Please try again later.');
            $this->_cSession->setFlash('alertType', 'warning');
        }

        $this->redirect('reportsEntities/showComments/projectId/'.$projectId.'/reportId/'.$reportId.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Setup related project
     * @param int $id
     * @param int $projectId
     */
    public function editReportAction($id = 0, $projectId = 0)
    {
        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $id);
        $reportsTypeId = ReportsEntities::model()->findByPk((int)$id);
        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);
        $reportCode = $reportsType->code;
        $this->_view->reportId = $id;

        ReportsProjectsComponent::prepareBackendAction('edit', 'reports', $reportCode, 'reportsEntityItems/manage');

        $project = ReportsProjects::model()->findByPk($projectId);
        $this->_view->projectName = $project->project_name;

        // Find all entities for this project
        $entitiesData = ReportsEntities::model()->findAll('project_id = :project_id AND '.CConfig::get('db.prefix').'reports_entities.id <> :id', array(':project_id' => $projectId, ':id' => $id ));
        $countEntities =count($entitiesData);
        $entitiesList = array('' => '');
        for($entity = 0; $entity < $countEntities; $entity++){
            $entitiesList[$entitiesData[$entity]['id']] = $entitiesData[$entity]['type_name'];
        }

        $this->_view->entitiesList = $entitiesList;
        $this->_view->reportName = $reportsType->name;
        $this->_view->entityId = $id;

        $this->_view->render('reportsEntities/editReport');
    }

    /**
     * Preview action handler
     * @params int $projectId
     * @params int $id
     */
    public function previewAction($projectId = 0, $reportId = 0)
    {
        $entity = $this->_checkActionAccess($reportId);
        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);
        $reportsTypeId = ReportsEntities::model()->findByPk((int)$reportId);
        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);
        $reportCode = $reportsType->code;

        ReportsProjectsComponent::prepareBackendAction('view', 'reports', $reportCode, 'reportsEntityItems/manage');

        $this->_preparePreview($projectId, $reportId);
    }

    /**
     * Download PDF action
     * @params int $projectId
     * @params int $reportId
     */
    public function getPdfAction($projectId = 0, $reportId = 0)
    {
        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);
        $reportsTypeId = ReportsEntities::model()->findByPk((int)$reportId);
        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);
        $reportCode = $reportsType->code;
        ReportsProjectsComponent::prepareBackendAction('view', 'reports', $reportCode, 'reportsEntityItems/manage');

        $content = $this->_preparePreview($projectId, $reportId, true);

        $position = ModulesSettings::model()->param('reports', 'report_pdf_type');
        $posPdf = 'P';
        if($position == 'LandScape'){
            $posPdf = 'L';
        }

		CPdf::config(array(
			'page_orientation' 	=> $posPdf, 		// [P=portrait, L=landscape]
			'unit' 				=> 'mm',			// [pt=point, mm=millimeter, cm=centimeter, in=inch]
			'page_format'		=> 'A4',
			'unicode'			=> true,
			'encoding'			=> 'UTF-8',
			'creator'			=> 'Report System',
			'author'			=> 'ApPHP',
			'title'				=> 'Report',
			'subject'			=> 'Report',
			'keywords'			=> '',
			'header_logo'		=> A::app()->getRequest()->getBaseUrl().'assets/modules/reports/images/logo.jpg',
			'header_logo_width'	=> '45',
			'header_title'		=> 'Report X',
			'header_enable'		=> false,
		));

        CPdf::createDocument($content,'Test content preview', 'I'); // 'I' - inline , 'D' - download
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = ReportsEntities::model()->findByPk($id);
        if(!$model){
            $this->redirect('reportsEntities/manage');
        }
        return $model;
    }

    /**
     * @param int $projectId
     * @param int $reportId
     */
    private function _checkValidationReport($projectId = 0, $reportId = 0)
    {
        if(empty($reportId) || !isset($reportId)){
            $this->_cSession->setFlash('alert', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertType', 'warning');
            if(isset($projectId)){
                $this->redirect('reportsEntities/manage/projectId/'.$projectId);
            }else{
                $this->redirect('reportsProjects/manage');
            }

        }
        $reportItem = ReportsEntities::model()->findByPk($reportId);

        if(isset($reportItem)){
            $reportTypeAccess = ReportsTypes::model()->find(CConfig::get('db.prefix').'reports_types.id = :id  AND '.CConfig::get('db.prefix').'reports_types.is_active = 1', array(':id' => $reportItem->type_id));
            if(!isset($reportTypeAccess)){
                $this->_cSession->setFlash('alert', A::t('reports', 'Wrong Report parameter!'));
                $this->_cSession->setFlash('alertType', 'warning');
                if(isset($projectId)){
                    $this->redirect('reportsEntities/manage/projectId/'.$projectId);
                }else{
                    $this->redirect('reportsProjects/manage');
                }
            }
        }else{
            $this->_cSession->setFlash('alert', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertType', 'warning');
            if(isset($projectId)){
                $this->redirect('reportsEntities/manage/projectId/'.$projectId);
            }else{
                $this->redirect('reportsProjects/manage');
            }
        }

        // If reportId exist but not assigned with current project. Checking
        if($projectId != $reportItem->project_id){
            $this->_cSession->setFlash('alert', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertType', 'warning');
            if(isset($projectId)){
                $this->redirect('reportsEntities/manage/projectId/'.$projectId);
            }else{
                $this->redirect('reportsProjects/manage');
            }
        }
    }

    /**
     * Check access to page with params
     * @param int $projectId
     */
    private function _checkAccessPage($projectId = 0)
    {
        $reportsProject = ReportsProjects::model()->findByPk($projectId);
        if(!isset($reportsProject)){
            $this->_view->projectId = $this->_cRequest->getQuery('projectId', '','');
            if($this->_view->projectId == ''){
                $this->_cSession->setFlash('alert', A::t('reports', 'Wrong project parameter!'));
                $this->_cSession->setFlash('alertType', 'warning');
                $this->redirect('reportsProjects/manage');

            }
            $reportsProject = ReportsProjects::model()->findByPk($this->_view->projectId);
            if(!isset($reportsProject)){
                $this->_cSession->setFlash('alert', A::t('reports', 'Wrong project parameter!'));
                $this->_cSession->setFlash('alertType', 'warning');
                $this->redirect('reportsProjects/manage');
            }
            $this->_view->projectId = $reportsProject->id;
            $this->_view->projectName = $reportsProject->project_name;
        }else{
            $this->_view->projectId = $projectId;
            $this->_view->projectName = $reportsProject->project_name;
        }

        if(A::app()->getSession()->hasFlash('message')){
            $alert = A::app()->getSession()->getFlash('message');
        }
    }

    /**
     * Check if passed record ID is valid
     * @param int $projectId
     * @param int $reportId
     * @param bool $return
     */
    private function _preparePreview($projectId = 0, $reportId = 0, $return = false)
    {
        // Data in one
        $dataReport = array();

        if($return){
            $this->_view->hideJs = 1;
        }else{
            $this->_view->hideJs = 0;
        }
        $dataReport[0]['missingFilename'] = '';
        $dataReport[1]['missingFilename'] = '';

        $numeric_fields = array('integer', 'integer (positive)', 'numeric', 'float');

        $rowsList = ReportsEntityItems::model()->findAll(array('condition' => 'entity_id = :entity_id AND status = 1 ', 'order' => 'id ASC'), array(':entity_id' => $reportId));
        $rowsCount = count($rowsList);

        $reportTypeId = ReportsEntities::model()->find('project_id = :project_id AND '.CConfig::get('db.prefix').'reports_entities.id = :id', array(':project_id' => $projectId, ':id' => $reportId));
        // Get report type item for checking activity
        $accessAbleFields = ReportsTypeItems::model()->findAll(
            array(
                'condition' => 'type_id = :type_id ',
                'order'     => 'sort_order ASC',
            ),
            array(':type_id' => $reportTypeId->type_id)
        );
        $dataReport[0]['accessAbleFields'] = $accessAbleFields;

        $commentsList = ReportsEntityComments::model()->findAll(
            array(
                'condition' => 'entity_id = :entity_id AND '.CConfig::get('db.prefix').'reports_entities_comments.is_active = 1 ',
                'order'     => 'display_date ASC'
            ),
            array(':entity_id' => $reportId)
        );
        $dataReport[0]['commentsList'] = $commentsList;

        $projectName = ReportsProjects::model()->findByPk((int)$projectId);

        if(isset($reportTypeId)){
            $fieldsNameList = ReportsTypeItems::model()->findAll(
                array(
                    'condition' => 'type_id = :type_id',
                    'order'     => 'sort_order ASC',
                ),
                array(':type_id' => $reportTypeId->type_id));
            $dataReport[0]['fieldsNameList'] = $fieldsNameList;
            $dataReport[0]['fieldsNameCount'] = count($fieldsNameList);
        }else{
            $this->redirect('error/404');
        }

        if(isset($reportTypeId)){
            $templateFile = ReportsTypes::model()->find('id = :id', array(':id' => $reportTypeId->type_id));
            $dataReport[0]['templateFile'] = $templateFile->template_name == '' ? 'default.tpl' : $templateFile->template_name;
            $dataReport[0]['projectData'] = array(
                'report_name'       => $templateFile->name,
            );
        }else{
            $dataReport[0]['templateFile'] = 'default.tpl';
        }

        if(CAuth::getLoggedId()){
            $dataReport[0]['missingFilename'] = $templateFile->template_name;
        }

        $accessAbleFieldsArray = array();
        if(isset($rowsList) && isset($projectName) ){
            $dataReport[0]['reportRows'] = $rowsList;
            $dataReport[0]['projectName'] = $projectName->project_name;

            // Adding format and decimal points to price
            $price = $projectName->project_price;
            $priceManage = $projectName->project_manage_price;
            $priceDesign = $projectName->project_design_price;
            $decimalPoints = 2;
            if($this->_view->format === 'european'){
                // 1,222.33 => '1.222,33'
                $price = str_replace(',', '', $price);
                $priceManage = str_replace(',', '', $priceManage);
                $priceDesign = str_replace(',', '', $priceDesign);
                $price = number_format((float)$price, $decimalPoints, ',', '.');
                $priceManage = number_format((float)$priceManage, $decimalPoints, ',', '.');
                $priceDesign = number_format((float)$priceDesign, $decimalPoints, ',', '.');
            }else{
                $price = number_format((float)$price, $decimalPoints);
                $priceManage = number_format((float)$priceManage, $decimalPoints);
                $priceDesign = number_format((float)$priceDesign, $decimalPoints);
            }

            $dataReport[0]['projectData'] += array(
                'client_name'               => $projectName->client_name,
                'project_created'           => date($this->_view->dateFormat, strtotime($projectName->created_at)),
                'client_address'            => $projectName->client_address,
                'client_email'              => $projectName->client_email,
                'client_phone'              => $projectName->client_phone,
                'project_price'             => $price,
                'project_manage_price'      => $priceManage,
                'project_design_price'      => $priceDesign,
                'logo_path'                 => A::app()->getRequest()->getBaseUrl().'assets/modules/reports/images/logo.jpg',
                'logo2_path'                => A::app()->getRequest()->getBaseUrl().'assets/modules/reports/images/logo.jpg',
                'project_name'              => $projectName->project_name,

            );
        }else{
            $this->redirect('reportsProjects/manage');
        }

        // Related report data
        $secondReport = ReportsEntities::model()->find('project_id = :project_id AND '.CConfig::get('db.prefix').'reports_entities.id = :id', array(':project_id' => $projectId, ':id' => $reportId));
        if($secondReport->related_report > 0)
        {
            $this->_view->relatedReport = 1;
            $rowsListSecond = ReportsEntityItems::model()->findAll(array('condition' => 'entity_id = :entity_id AND status = 1 ', 'order' => 'id ASC'), array(':entity_id' => $secondReport->related_report));
            $rowsCountSecond = count($rowsListSecond);

            $reportTypeIdSecond = ReportsEntities::model()->find('project_id = :project_id AND '.CConfig::get('db.prefix').'reports_entities.id = :id', array(':project_id' => $projectId, ':id' => $secondReport->related_report));

            // Get report type item for checking activity
            $accessAbleFieldsSecond = ReportsTypeItems::model()->findAll(
                array(
                    'condition' => 'type_id = :type_id ',
                    'order'     => 'sort_order ASC',
                ),
                array(':type_id' => $reportTypeIdSecond->type_id)
            );
            $dataReport[1]['accessAbleFields'] = $accessAbleFieldsSecond;

            $commentsListSecond = ReportsEntityComments::model()->findAll(
                array(
                    'condition' => 'entity_id = :entity_id AND '.CConfig::get('db.prefix').'reports_entities_comments.is_active = 1 ',
                    'order'     => 'display_date ASC'
                ),
                array(':entity_id' => $secondReport->related_report)
            );
            $dataReport[1]['commentsList'] = $commentsListSecond;

            $projectName = ReportsProjects::model()->findByPk((int)$projectId);

            if(isset($reportTypeIdSecond)){
                $fieldsNameListSecond = ReportsTypeItems::model()->findAll(
                    array(
                        'condition' => 'type_id = :type_id',
                        'order'     => 'sort_order ASC',
                    ),
                    array(':type_id' => $reportTypeIdSecond->type_id));
                $dataReport[1]['fieldsNameList'] = $fieldsNameListSecond;
                $dataReport[1]['fieldsNameCount'] = count($fieldsNameListSecond);
            }else{
                $this->redirect('error/404');
            }

            if(isset($reportTypeIdSecond)){
                $templateFileSecond = ReportsTypes::model()->find('id = :id', array(':id' => $reportTypeIdSecond->type_id));
                $dataReport[1]['templateFile'] = $templateFileSecond->template_name == '' ? 'default.tpl' : $templateFileSecond->template_name;
                $dataReport[1]['projectData'] = array(
                    'report_name'       => $templateFileSecond->name,
                );

            }else{
                $dataReport[1]['templateFile'] = 'default.tpl';
            }

            if(CAuth::getLoggedId()){
                $dataReport[1]['missingFilename'] = $templateFileSecond->template_name;
            }

            $accessAbleFieldsArraySecond = array();
            if(isset($rowsListSecond) && isset($projectName) ){
                $dataReport[1]['reportRows'] = $rowsListSecond;
                $dataReport[1]['projectName'] = $projectName->project_name;

                // Adding format and decimal points to price
                $price = $projectName->project_price;
                $priceManage = $projectName->project_manage_price;
                $priceDesign = $projectName->project_design_price;
                $decimalPoints = 2;
                if($this->_view->format === 'european'){
                    // 1,222.33 => '1.222,33'
                    $price = str_replace(',', '', $price);
                    $priceManage = str_replace(',', '', $priceManage);
                    $priceDesign = str_replace(',', '', $priceDesign);
                    $price = number_format((float)$price, $decimalPoints, ',', '.');
                    $priceManage = number_format((float)$priceManage, $decimalPoints, ',', '.');
                    $priceDesign = number_format((float)$priceDesign, $decimalPoints, ',', '.');
                }else{
                    $price = number_format((float)$price, $decimalPoints);
                    $priceManage = number_format((float)$priceManage, $decimalPoints);
                    $priceDesign = number_format((float)$priceDesign, $decimalPoints);
                }

                $dataReport[1]['projectData'] += array(
                    'client_name'               => $projectName->client_name,
                    'project_created'           => date($this->_view->dateFormat, strtotime($projectName->created_at)),
                    'client_address'            => $projectName->client_address,
                    'client_email'              => $projectName->client_email,
                    'client_phone'              => $projectName->client_phone,
                    'project_price'             => $price,
                    'project_manage_price'      => $priceManage,
                    'project_design_price'      => $priceDesign,
                    'logo_path'                 => A::app()->getRequest()->getBaseUrl().'assets/modules/reports/images/logo.jpg',
                    'logo2_path'                => A::app()->getRequest()->getBaseUrl().'assets/modules/reports/images/logo.jpg',
                    'project_name'              => $projectName->project_name,

                );

            }else{
                $this->redirect('reportsProjects/manage');
            }
        }

        $this->_view->dataReport = $dataReport;

        if($return){
            return $this->_view->render('reportsEntities/preview', true, true);
        }else{
            $this->_view->render('reportsEntities/preview', true);
        }
    }

    /**
     * Show tabs
     * @param int $projectId
     */
    private function _displayTabs($projectId = 0)
    {
        // Check whether to show reports in separate tabs
        $this->_view->showReportTabs = ModulesSettings::model()->param('reports', 'show_report_tabs');
        $this->_view->allReports = $this->_view->showReportTabs ? ReportsEntities::model()->findAll(
            array(
                'condition' => 'project_id = :project_id AND '.CConfig::get('db.prefix').'reports_types.is_active = 1 ',
                'order'     => 'sort_order ASC'
            ),
             array(':project_id' => $projectId)
        ) : '';
    }
 
}
