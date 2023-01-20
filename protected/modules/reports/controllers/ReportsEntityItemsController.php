<?php
/**
 * ReportsEntityItems controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkActionAccess
 * indexAction              _checkAccessPage 
 * manageAction				_checkValidationReport
 * addAction				_getFieldsDataForManage
 * editAction				_getFieldsData
 * deleteAction				_assignWysiwygToTexareas
 * changeStatusAction		_displayTabs
 * addMassAction
 *
 */

namespace Modules\Reports\Controllers;

// Modules
use \Modules\Reports\Components\ReportsProjectsComponent,
	\Modules\Reports\Models\ReportsTypes,
	\Modules\Reports\Models\ReportsTypeItems,
	\Modules\Reports\Models\ReportsEntities,
	\Modules\Reports\Models\ReportsEntityItems,
	\Modules\Reports\Models\ReportsProjects;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CHash,
    \CDebug,
    \CFile,
	\CWidget;

// Directy
use \LocalTime,
	\Modules,
	\Website,
	\Bootstrap,
	\Languages,
	\ModulesSettings;

class ReportsEntityItemsController extends CController
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

		$this->_cRequest = A::app()->getRequest();
		$this->_cSession = A::app()->getSession();

		if(CAuth::isLoggedInAsAdmin()){
            // Set meta tags according to active Reports
            Website::setMetaTags(array('title'=>A::t('reports', 'Report Items Management')));
			// Set backend mode
			Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->backendPath = $this->_backendPath;

            // Fetch site settings info
            $this->_settings = Bootstrap::init()->getSettings();
            $this->_view->dateTimeFormat = $this->_settings->datetime_format;
            $this->_view->dateFormat = $this->_settings->date_format;
            $this->_view->numberFormat = $this->_settings->number_format;
            
            $this->_view->tabs = ReportsProjectsComponent::prepareTab('reportsprojectstab');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('reportsEntityItems/manage');
    }
    
    /**
     * Manage action handler
     * @param int $projectId
     * @param int $reportId
     */
    public function manageAction($projectId = 0, $reportId = 0)
    {
        Website::prepareBackendAction('manage', 'reports', 'reportsEntityItems/manage', false);

        if((!isset($projectId) || empty($projectId)) || (!isset($reportId) || empty($reportId))){
            $this->_view->projectId = $this->_cRequest->getQuery('projectId', '','');
            $this->_view->reportId = $this->_cRequest->getQuery('reportId', '','');
        }else{
            $this->_view->projectId = $projectId;
            $this->_view->reportId = $reportId;
        }

        $this->_checkAccessPage($this->_view->projectId);
        $this->_checkValidationReport($this->_view->projectId, $this->_view->reportId);

        $this->_view->entityItems = ReportsTypeItems::model()->findAll('type_id = :type_id and is_active = :is_active', array(':type_id' => $this->_view->reportId, ':is_active' => 1));
        $this->_view->fieldsList = $this->_getFieldsDataForManage($this->_view->reportId);
        $reportsProject = ReportsProjects::model()->findByPk($this->_view->projectId);
        $reportsEntity = ReportsEntities::model()->findByPk($this->_view->reportId);
        $reportsType = ReportsTypes::model()->findByPk($reportsEntity->type_id);
        $this->_view->projectName = $reportsProject->project_name;
        $this->_view->reportName = $reportsType->name;
        $this->_view->reportCode = $reportsType->code;

		if($this->_cSession->hasFlash('alert')){
            $alert = $this->_cSession->getFlash('alert');
            $alertType = $this->_cSession->getFlash('alertType');
			
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
		}

        $this->_displayTabs($projectId);

        $this->_view->render('reportsEntityItems/manage');
    }

    /**
     * Add new row action handler
     */
    public function addAction()
    {

        $this->_view->projectId = $this->_cRequest->getQuery('projectId', '','');
        $this->_view->reportId = $this->_cRequest->getQuery('reportId', '','');

        $this->_checkAccessPage($this->_view->projectId);
        $this->_checkValidationReport($this->_view->projectId, $this->_view->reportId);

        $reportsTypeId = ReportsEntities::model()->findByPk((int)$this->_view->reportId);

        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);
        $reportsProject = ReportsProjects::model()->findByPk($this->_view->projectId);

        $this->_view->projectName = $reportsProject->project_name;
        $this->_view->reportName = $reportsType->name;

        $reportCode = $reportsType->code;
        $this->_view->projectPrice = $reportsProject->project_price;
        $this->_view->projectManagePrice = $reportsProject->project_manage_price;
        $this->_view->projectDesignPrice = $reportsProject->project_design_price;
        $this->_view->contractorPrice = $reportsProject->contract_price;
        $this->_view->fieldsList = $this->_getFieldsData('add', '', $reportsType->id, $this->_view->projectId, $this->_view->reportId);

        ReportsProjectsComponent::prepareBackendAction('edit', 'reports', $reportCode, 'reportsEntityItems/manage');

		$this->_assignWysiwygToTexareas($this->_view->fieldsList);

        $this->_displayTabs($this->_view->projectId);

        $this->_view->render('reportsEntityItems/add');
    }

    /**
     * Edit Reports action handler
     * @param int $id 
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsEntityItems/manage', false);
        $reportsEntityItem = $this->_checkActionAccess($id);

        $this->_view->projectId = $this->_cRequest->getQuery('projectId', '','');
        $this->_view->reportId = $this->_cRequest->getQuery('reportId', '','');

        $this->_checkAccessPage($this->_view->projectId);
        $this->_checkValidationReport($this->_view->projectId, $this->_view->reportId);

        $reportsTypeId = ReportsEntities::model()->findByPk((int)$this->_view->reportId);

        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);
        $reportsProject = ReportsProjects::model()->findByPk($this->_view->projectId);

        $this->_view->projectName = $reportsProject->project_name;
        $this->_view->reportName = $reportsType->name;

        $reportCode = $reportsType->code;
        $this->_view->projectPrice = $reportsProject->project_price;
        $this->_view->projectManagePrice = $reportsProject->project_manage_price;
        $this->_view->projectDesignPrice = $reportsProject->project_design_price;
        $this->_view->contractorPrice = $reportsProject->contract_price;

        ReportsProjectsComponent::prepareBackendAction('edit', 'reports', $reportCode, 'reportsEntityItems/manage');

        $image = $this->_cRequest->getQuery('image', '','');
        $imageFile = $this->_cRequest->getQuery('imgfile', '','');
        $file = $this->_cRequest->getQuery('file', '','');
        $fileName = $this->_cRequest->getQuery('filename', '','');
        // Delete the image file
        if($image === 'delete'){
            $alert = '';
            $alertType = '';
            $image = 'assets/modules/reports/images/'.$reportsEntityItem->$imageFile;
            $reportsEntityItem->$imageFile = '';
            if($reportsEntityItem->save()){
                // Delete the images
                if(CFile::deleteFile($image)){
                    $alert = A::t('reports', 'Image has been successfully deleted!');
                    $alertType = 'success';
                }else{
                    $alert = A::t('reports', 'Image cannot be deleted!');
                    $alertType = 'warning';
                }
            }else{
                $alert = A::t('gallery', 'Image cannot be deleted!');
                $alertType = 'error';
            }
            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        // Delete the file
        if($file === 'delete'){
            $alert = '';
            $alertType = '';
            $file = 'assets/modules/reports/files/upload/'.$reportsEntityItem->$fileName;
            $reportsEntityItem->$fileName = '';
            if($reportsEntityItem->save()){
                // Delete the images
                if(CFile::deleteFile($file)){
                    $alert = A::t('reports', 'Image has been successfully deleted!');
                    $alertType = 'success';
                }else{
                    $alert = A::t('reports', 'Image cannot be deleted!');
                    $alertType = 'warning';
                }
            }else{
                $alert = A::t('gallery', 'Image cannot be deleted!');
                $alertType = 'error';
            }
            if(!empty($alert)){
                $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
            }
        }

        $this->_view->fieldsList = $this->_getFieldsData('edit', $id, $reportsType->id, $this->_view->projectId, $this->_view->reportId);
        $this->_view->reportsEntityItem = $reportsEntityItem;
		
		$this->_assignWysiwygToTexareas($this->_view->fieldsList);

        $this->_displayTabs($this->_view->projectId);
		
        $this->_view->render('reportsEntityItems/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'reports', 'reportsEntityItems/manage', false);

        $projectId = $this->_cRequest->getQuery('projectId', '','');
        $reportId = $this->_cRequest->getQuery('reportId', '','');

        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);

        $model = $this->_checkActionAccess($id);
        if(isset($model)){
            if($model->delete()){
                if($model->getError()){
                    $alert = A::t('reports', 'Report Item cannot be deleted!');
                    $alertType = 'warning';
                }else{
                    $alert = A::t('reports', 'Report Item has been successfully deleted!');
                    $alertType = 'success';
                }
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = A::t('core', 'This operation is blocked in Demo Mode!');
                    $alertType = 'warning';
                }else{
                    $alert = A::t('reports', 'Report Item cannot be deleted!');
                    $alertType = 'error';
                }
            }
        }
	
		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

		$this->redirect('reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId);
    }

    /**
     * Change report type item state method
     * @param int $id
     * @param int $page 	the page number
     */
    public function changeStatusAction($id = 0, $page = 1)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsEntityItems/manage', false);

        $projectId = $this->_cRequest->getQuery('projectId', '','');
        $reportId = $this->_cRequest->getQuery('reportId', '','');

        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);

        $reportEntityItem = $this->_checkActionAccess($id);

		if(APPHP_MODE == 'demo'){
			$alert = A::t('core', 'This operation is blocked in Demo Mode!');
			$alertType = 'warning';
		}else{
			$changeResult = ReportsEntityItems::model()->updateByPk($id, array('status' => ':status'), '', array(':status' => ($reportEntityItem->status == 1 ? '0' : '1')));
			if($changeResult){
				$alert = A::t('reports', 'Report Item status has been changed!');
				$alertType = 'success';
			}else{
				$alert = A::t('reports', 'Report Item status cannot be changed!');
				$alertType = 'error';
			}
		}

		$this->_cSession->setFlash('alert', $alert);
		$this->_cSession->setFlash('alertType', $alertType);

        $this->redirect('reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId.(!empty($page) ? '?page='.(int)$page : 1));
    }

    /**
     * Add some empty rows
     * @param int $id
     * @param int $count
     */
    public function addMassAction($id = 0, $count = 0)
    {
        Website::prepareBackendAction('edit', 'reports', 'reportsEntityItems/manage', false);

        $alert = '';
        $alertType = '';

        $projectId = $this->_cRequest->getQuery('projectId', 0);
        $reportId = $this->_cRequest->getQuery('reportId', 0);
        $count = (int)$this->_cRequest->getQuery('count', 0);
        // Limit to create empty records
        if($count > 30) $count = 30; 
		
        $this->_checkAccessPage($projectId);
        $this->_checkValidationReport($projectId, $reportId);
        $reportsTypeId = ReportsEntities::model()->find(
            array(
                'condition' => 'project_id = :project_id AND '.CConfig::get('db.prefix').'reports_entities.id = :id'
            ),
            array(
                ':project_id' => $projectId,
                ':id' => $reportId
            )
        );

        $reportsType = ReportsTypes::model()->findByPk($reportsTypeId->type_id);

        $reportCode = $reportsType->code;

        ReportsProjectsComponent::prepareBackendAction('edit', 'reports', $reportCode, 'reportsEntityItems/manage');

        $reportEntityItem = $this->_checkActionAccess($id);

        $result = ReportsProjectsComponent::addEmptyRows($reportId, $count);

        if($result){
            if($count == 1){
                $this->_cSession->setFlash('alertChange', A::t('reports', 'An empty row has been successfully added to this report!'));
                $this->_cSession->setFlash('alertChangeType', 'success');
            }else{
                $this->_cSession->setFlash('alertChange', A::t('reports', 'The {number} empty rows have been successfully added to this report!', array('{number}'=>$count)));
                $this->_cSession->setFlash('alertChangeType', 'success');
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $this->_cSession->setFlash('alertChange', A::t('core', 'This operation is blocked in Demo Mode!'));
                $this->_cSession->setFlash('alertChangeType', 'warning');
            }else{
                $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong number of empty rows, possible values are from 1 to 30! Please re-enter!'));
                $this->_cSession->setFlash('alertChangeType', 'error');
            }
        }

        $this->redirect('reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId);
    }

    /**
     * @param int $reportTypeId
     * @return array $fieldsList
     */
    private function _getFieldsDataForManage($reportTypeId = 0)
    {
        $reportType = ReportsEntities::model()->find(CConfig::get('db.prefix').'reports_entities.id = :id', array(':id' => $reportTypeId));

        $reportsTypeItems = ReportsTypeItems::model()->findAll(
            array(
                'condition' => 'type_id = :type_id',
                'order'     => 'sort_order ASC',
            ),
            array(':type_id' => $reportType->type_id));
        $reportsRows = ReportsEntityItems::model()->findAll('entity_id = :entity_id AND status = 1', array(':entity_id' => $reportTypeId));
        // Check for exist must have
        $fieldsList = array();
        $imageFancyList = array();
        $startNumber = 0;
        $fieldNumber = 1;
        $rowNumber = 0;
        $numeric_fields = array('integer', 'integer (positive)', 'numeric', 'float', 'percent');
        $rows = array();
        $this->_view->reportsRowsCount = count($reportsRows);
        for($row = 0; $row < $this->_view->reportsRowsCount; $row++){
            $rows[] = $reportsRows[$row]['id'];
            if($reportsRows[$row]['id']){

            }
        }

        foreach($reportsTypeItems as $fields){
            if($fields['is_active'] == 1){
                switch($fields['field_type']){
                    case '':
                        break;
                    case 'autoIncrement':
                        if(in_array($fields['field_validation_type'], $numeric_fields)){
                            if($fields['field_validation_type'] == 'float'){
                                $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'decimal', 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'format'=>$this->_view->numberFormat, 'align'=>'right', 'default' => $fields['field_default_value'], 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'sortType' => 'numeric', 'decimalPoints'=>'2', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                            }else{
                                $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'decimal', 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'format'=>$this->_view->numberFormat, 'align'=>'right', 'default' => $fields['field_default_value'], 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'sortType' => 'numeric', 'decimalPoints'=>'', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                            }
                        }else{
                            $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'label', 'align'=>'left', 'default' => $fields['field_default_value'], 'sortType' => 'string', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        }
                        break;
                    case 'textbox':
                        if(in_array($fields['field_validation_type'], $numeric_fields)){
                            if($fields['field_validation_type'] == 'float'){
                                $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'decimal', 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'align'=>'', 'format'=>$this->_view->numberFormat, 'decimalPoints'=>'2', 'default' => $fields['field_default_value'], 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'sortType' => 'numeric', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                            }elseif($fields['field_validation_type'] == 'percent'){
								$currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'decimal', 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'align'=>'', 'format'=>$this->_view->numberFormat, 'decimalPoints'=>'1', 'default' => $fields['field_default_value'], 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'sortType' => 'numeric', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                            }else{
                                $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'decimal', 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'align'=>'', 'format'=>$this->_view->numberFormat, 'decimalPoints'=>'', 'default' => $fields['field_default_value'], 'class'=>'right', 'headerClass'=>'right', 'isSortable'=>true, 'sortType' => 'numeric', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                            }
                        }else{
                            $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'label', 'align'=>'', 'default' => $fields['field_default_value'], 'sortType' => 'string', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'sortType' => 'numeric', 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        }
                        break;
                    case 'textarea':
                        $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'html', 'align'=>'', 'width'=>'', 'default' => $fields['field_default_value'], 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                    case 'imageUpload':
                        $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'image',  'width' => '70px', 'align'=>'center', 'imagePath'=>'assets/modules/reports/images/', 'defaultImage'=>'no_image.png', 'imageWidth'=>'64px', 'imageHeight'=>'64px', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'htmlOptions'=>array('style' => 'width:70px;'), 'prependCode'=>'<a class="fancybox" rel="reference_picture" href="#">', 'appendCode'=>'</a>');
                        $imageFancyList[] = $fieldNumber;
                        break;
                    case 'fileUpload':
                        $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'link',  'width'=>$fields['field_width']!='' ? $fields['field_width'] : '', 'align'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>false, 'linkUrl'=>'assets/modules/reports/files/upload/{field_'.$fieldNumber.'}', 'linkText'=>'{field_'.$fieldNumber.'}', 'htmlOptions'=>array('download' => 'file'), 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                    case 'datetime':
                        $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'datetime', 'align'=>'', 'default' => '', 'width'=>'120px', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(null=>'--'), 'format' => $this->_view->dateFormat, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                    default:
                        $currentField = array('title'=> A::t('reports', $fields['field_title']), 'type'=>'label', 'align'=>'', 'default' => $fields['field_default_value'], 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                }
                if($fields['show_on_mainview'] == 1){
                    $fieldsList['field_'.$fieldNumber] = $currentField;
                }
            }

            $fieldNumber++;
        }

        $this->_view->reportsRows = $rows;
        $this->_view->reportsFieldsImageNumbers = $imageFancyList;
        $this->_view->reportsFieldsImageNumbersCount = count($imageFancyList);

        return $fieldsList;
    }

    /**
	 * Returns fields data
     * @param string $mode - edit and add
     * @param int $id
     * @param int $reportId
     * @param int $projectId
     * @param int $entityId
     * @return array
     */
    private function _getFieldsData($mode = 'edit', $id = 0, $reportId = 0, $projectId = 0, $entityId = 0)
    {
        $reportsType = ReportsTypeItems::model()->findAll(
            array(
                'condition' => 'type_id = :type_id',
                'order'     => 'sort_order ASC'
            ),
            array(':type_id' => $reportId)
        );
        // Check for exist must have
        $lastItemIndex = 0;
        $numeric_fields = array('integer', 'integer (positive)', 'numeric', 'float', 'percent');
        $int_fields = array('integer', 'integer (positive)', 'numeric', 'percent');
        // Get project price
        $projectData = ReportsProjects::model()->find('id = :id', array(':id' => $projectId));
        $projectPrice = $projectData->project_price;

        $scriptList = '';
        $reportScript = ReportsTypes::model()->findByPk($reportId);
        if(isset($reportScript)){
            $scriptList = $reportScript->js_event_handler;
        }

        $fieldsList = array();
        $fieldNumber = 1;
        foreach($reportsType as $fields){

			$htmlOptions = array();
			if(!empty($fields['field_placeholder'])) $htmlOptions['placeholder'] = $fields['field_placeholder'];
			if(!empty($fields['field_maxlength'])) $htmlOptions['maxlength'] = $fields['field_maxlength'];
			if(!empty($fields['field_width'])) $htmlOptions['style'] = 'width:'.htmlentities($fields['field_width']);
			if($fields['is_active'] != 1){
                $currentField = array('type'=>'data', 'default' => $fields['field_default_value'] );
            }else{
                switch($fields['field_type']){
                    case '':'';
                        break;
                    case 'autoIncrement':
                        $allIndex = ReportsEntityItems::model()->findAll(array('condition' =>'entity_id = :entity_id' ),array(':entity_id' => $entityId));
                        $indexCount = count($allIndex);
                        $indexMax = 0;
                        for($idx = 0; $idx < $indexCount; $idx++){
                            CDebug::c($allIndex[$idx]['field_'.$fieldNumber]);
                            (int)$allIndex[$idx]['field_'.$fieldNumber] > (int)$indexMax ? $indexMax = $allIndex[$idx]['field_'.$fieldNumber] : '';
                        }
                        $lastItemIndex = $indexMax+1;
                        $currentField = array('type' => 'textbox', 'title'=>A::t('reports', $fields['field_title']), 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '85px', 'tooltip'=>$fields['field_tooltip'], 'default'=>(int)$lastItemIndex, 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true'): $htmlOptions, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                    case 'textbox':
                        if(in_array($fields['field_validation_type'], $numeric_fields)){
                            if($fields['field_validation_type'] == 'percent'){
                                $currentField = array('type'=>'textbox', 'title'=>A::t('reports', $fields['field_title']), 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '140px', 'tooltip'=>$fields['field_tooltip'], 'default'=> $fields['field_default_value'] == '' ? 0 : $fields['field_default_value'], 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true', 'class' => 'percent_validator', 'data-type' => 'numeric'): $htmlOptions+array('data-type' => 'numeric', 'class' => 'percent_validator'),  'prependCode' => $fields['field_prepend_code'] != ''? $fields['field_prepend_code'].'&nbsp;' : '', 'appendCode'=> $fields['field_append_code']!= ''? $fields['field_append_code'].'&nbsp;':''  );
                            }else{
                                $currentField = array('type'=>'textbox', 'title'=>A::t('reports', $fields['field_title']), 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '140px', 'tooltip'=>$fields['field_tooltip'], 'default'=> $fields['field_default_value'] == '' ? 0 : $fields['field_default_value'], 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true', 'data-type' => 'numeric'): $htmlOptions+array('data-type' => 'numeric'),  'prependCode' => $fields['field_prepend_code'] != ''? $fields['field_prepend_code'].'&nbsp;' : '', 'appendCode'=> $fields['field_append_code']!= ''? $fields['field_append_code'].'&nbsp;':''  );
                            }
                        }else{
                            $currentField = array('type'=>'textbox', 'title'=>A::t('reports', $fields['field_title']), 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '140px', 'tooltip'=>$fields['field_tooltip'], 'default'=>$fields['field_default_value'] == '' ? '' : $fields['field_default_value'], 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true'): $htmlOptions, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        }

                        break;
                    case 'textarea':
                        $currentField = array('type'=>'textarea', 'title'=>A::t('reports', $fields['field_title']), 'tooltip'=>$fields['field_tooltip'], 'default'=>$fields['field_default_value'], 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true'): $htmlOptions, 'prependCode'=>$fields['field_prepend_code'].'&nbsp; ', 'appendCode'=>$fields['field_append_code']);
                        break;
                    case 'imageUpload':
                        $currentField = array(
                            'type'=>'imageupload',
                            'title'=> $fields['field_title'],
                            'tooltip'=>$fields['field_tooltip'],
                            'default'=> $fields['field_default_value'],
                            'validation'=>array('required'=>$fields['field_required'], 'type'=>'image', 'targetPath'=>'assets/modules/reports/images/', 'maxSize'=>'900k', 'mimeType'=>'image/jpeg, image/jpg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'htmlOptions'=>array()),
                            'imageOptions'  => array('showImage'=>true, 'imageClass'=>'avatar'),
                            'fileOptions' => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/images/')
                        );
                        if($mode == 'edit'){
                            $currentField['deleteOptions'] = array('showLink'=>true, 'linkUrl'=>'reportsEntityItems/edit/id/'.$id.'/projectId/'.$projectId.'/reportId/'.$entityId.'/image/delete/imgfile/field_'.$fieldNumber, 'linkText'=>'Delete');
                        }else{
                            //  $currentField['deleteOptions'] = array('showLink'=>true, 'linkUrl'=>'reportsEntityItems/edit/id/'.$id.'/projectId/'.$projectId.'/reportId/'.$reportId.'/image/delete/imgfile/field_'.$fieldNumber, 'linkText'=>'Delete');
                        }
                        break;
                    case 'fileUpload':
                        $currentField = array(
                            'type'=>'fileupload',
                            'title'=> $fields['field_title'],
                            'tooltip'=>$fields['field_tooltip'],
                            'default'=> $fields['field_default_value'],
                            'validation'=>array('required'=>$fields['field_required'], 'type'=>'file', 'targetPath'=>'assets/modules/reports/files/upload/', 'maxSize'=>'200M', 'mimeType'=>'application/pdf', 'fileName'=>'p'.$projectId.'_'.CHash::getRandomString(10), 'htmlOptions'=>array()),
                            'iconOptions'=>array('showType'=>true, 'showFileName'=>true, 'showFileSize'=>true),
                            'fileOptions' => array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/reports/files/upload/')
                        );
                        if($mode == 'edit'){
                            $currentField['deleteOptions'] = array('showLink'=>true, 'linkUrl'=>'reportsEntityItems/edit/id/'.$id.'/projectId/'.$projectId.'/reportId/'.$entityId.'/file/delete/filename/field_'.$fieldNumber, 'linkText'=>'Delete');
                        }else{
                            //  $currentField['deleteOptions'] = array('showLink'=>true, 'linkUrl'=>'reportsEntityItems/edit/id/'.$id.'/projectId/'.$projectId.'/reportId/'.$reportId.'/image/delete/imgfile/field_'.$fieldNumber, 'linkText'=>'Delete');
                        }
                        break;
                    case 'datetime':
                        $currentField = array('type'=>'datetime', 'title'=>A::t('reports', $fields['field_title']), 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '100px', 'validation'=>array('required'=>$fields['field_required'], 'type'=>'date', 'maxLength'=>10), 'default'=>$fields['field_default_value'], 'tooltip'=>$fields['field_tooltip'], 'htmlOptions'=>array('maxlength'=>10, 'style'=>'width:100px'), 'definedValues'=>array(), 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                    default:
                        $currentField = array('type'=>'textbox', 'title'=>A::t('reports', $fields['field_title']), 'tooltip'=>$fields['field_tooltip'], 'width'=>$fields['field_width']!='' ? $fields['field_width'] : '85px', 'default'=>$fields['field_default_value'], 'validation'=>array('required'=>$fields['field_required'], 'type'=>$fields['field_validation_type'], 'maxLength' => $fields['field_maxlength']), 'htmlOptions'=> $fields['readonly'] == 1 ? $htmlOptions +array('readonly' => 'true'): $htmlOptions, 'prependCode'=>$fields['field_prepend_code'], 'appendCode'=>$fields['field_append_code']);
                        break;
                }
            }
            $fieldsList['field_'.$fieldNumber] = $currentField;
            $fieldNumber++;
        }
        // Add project price to js handler
        $scriptList = str_ireplace('{{project_price}}', $projectPrice, $scriptList);

        $this->_view->scriptList = $scriptList;
        return $fieldsList;
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = ReportsEntityItems::model()->findByPk($id);
        $projectId = $this->_cRequest->getQuery('projectId', '','');
        $reportId = $this->_cRequest->getQuery('reportId', '','');
        $reportsProject = ReportsProjects::model()->findByPk($projectId);
        $reportsEntity = ReportsEntities::model()->findByPk($reportId);

        if(!$model){
            if(!isset($reportsProject) || !isset($reportsEntity)){
                $this->redirect('reportsEntityItems/manage');
            }else{
                if(isset($reportsProject) && isset($reportsEntity)){
                    if($projectId != $reportsEntity->project_id){
                        $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report Row parameter!'));
                        $this->_cSession->setFlash('alertChangeType', 'warning');
                        $this->redirect('reportsEntityItems/manage/projectId/'.$projectId.'/reportId/'.$reportId);
                    }else{
                        $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report Item parameter!'));
                        $this->_cSession->setFlash('alertChangeType', 'warning');
                        $this->redirect('reportsEntities/manage/projectId/'.$projectId);
                    }
                }elseif(isset($reportsProject)){
                    $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report Item parameter!'));
                    $this->_cSession->setFlash('alertChangeType', 'warning');
                    $this->redirect('reportsEntities/manage/projectId/'.$projectId);
                }
            }
        }
		
        return $model;
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
                $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Project parameter!'));
                $this->_cSession->setFlash('alertChangeType', 'warning');
                $this->redirect('reportsProjects/manage');

            }
            $reportsProject = ReportsProjects::model()->findByPk($this->_view->projectId);
            if(!isset($reportsProject)){
                $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Project parameter!'));
                $this->_cSession->setFlash('alertChangeType', 'warning');
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
     * @param int $projectId
     * @param int $reportId
     */
    private function _checkValidationReport($projectId = 0, $reportId = 0)
    {
        if(empty($reportId) || !isset($reportId)){
            $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertChangeType', 'warning');
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
                $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report parameter!'));
                $this->_cSession->setFlash('alertChangeType', 'warning');
                if(isset($projectId)){
                    $this->redirect('reportsEntities/manage/projectId/'.$projectId);
                }else{
                    $this->redirect('reportsProjects/manage');
                }
            }
        }else{
            $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertChangeType', 'warning');
            if(isset($projectId)){
                $this->redirect('reportsEntities/manage/projectId/'.$projectId);
            }else{
                $this->redirect('reportsProjects/manage');
            }
        }

        // If reportId exist but not assigned with current project. Checking
        if($projectId != $reportItem->project_id){
            $this->_cSession->setFlash('alertChange', A::t('reports', 'Wrong Report parameter!'));
            $this->_cSession->setFlash('alertChangeType', 'warning');
            if(isset($projectId)){
                $this->redirect('reportsEntities/manage/projectId/'.$projectId);
            }else{
                $this->redirect('reportsProjects/manage');
            }
        }
    }

    /**
     * Assigns WYSIWYG editor to textareas
     * @param array $fieldsList
     * @return void	
     */
    private function _assignWysiwygToTexareas($fieldsList = array())
    {
		if(!is_array($fieldsList)) return false;
		
		// Check whether to enable WYSIWYG editors
		if(!ModulesSettings::model()->param('reports', 'enable_wysiwyg_editors')) return false;

		$fieldInd = 0;
		foreach($fieldsList as $key => $val){
			$type = isset($val['type']) ? $val['type'] : '';
			$fieldInd++;
			if($type == 'textarea'){
				$fieldId = 'frmReportsTypeItemsAdd_field_'.$fieldInd;
				A::app()->getClientScript()->registerScript('setTinyMceEditor_'.$key, 'setEditor("'.$fieldId.'",false,"simplest");', 5);	
			}
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
