<?php
/**
* ReportsComponent
*
* PUBLIC:                  	PRIVATE
* -----------              	------------------
* init
* prepareTab
* drawShortcode
* addEmptyRows
* prepareBackendAction
* 
* STATIC
* -------------------------------------------
* init
* 
*/

namespace Modules\Reports\Components;

// Modules
use \Modules\Reports\Models\ReportsEntities,
	\Modules\Reports\Models\ReportsTypes,
	\Modules\Reports\Models\ReportsTypeItems,
    \Modules\Reports\Models\ReportsEntityItems;

// Global
use \A,
	\CAuth,
	\CWidget,
	\CComponent,
	\CDatabase,
	\CHtml,
	\CConfig,
	\CTime;

// CMF
use \Admins,
	\Website,
	\Bootstrap,
	\LocalTime,
	\ModulesSettings;


class ReportsProjectsComponent extends CComponent{

	const NL = "\n";

    public static function init()
	{
	    return parent::init(__CLASS__);
	}

    /**
     * Prepares reports module tabs
     * @param string $activeTab
     * @param int $menuCatId
     * @param int $menuCatItemId
     */
    public static function prepareTab($activeTab = 'info', $menuCatId = '', $menuCatItemId = '')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('reports', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/reports', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('reports', 'Projects') => array('href'=>'ReportsProjects/index', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'reportsprojectstab' ? true : false)),
				A::t('reports', 'Report Types') => array('href'=>'ReportsTypes/index', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'reportstypestab' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }

	/**
	 * Draws shortcode output
	 * @param array $params
	 */
	public static function drawShortcode($params = array())
	{
		return '';
    }

    /**
     * @param int $entity_id
     * @param int $count
     * @return bool
     */
    public static function addEmptyRows($entity_id = 0, $count = 0)
    {
        $result = false;
		
        $reportType = ReportsEntities::model()->findBypk((int)$entity_id);
        $reportTypeItem = ReportsTypeItems::model()->findAll('type_id = :type_id', array(':type_id' => $reportType->type_id ));

        $fieldsCount = count($reportTypeItem);

        $content = '';
        for($entity = 0; $entity < $count; $entity++){
			
            $fieldNumber = 1;
            $incNumber = 0;
            $fieldNameList = '';
            $fieldNameListForSQL = '';
            $fieldNameListForSQLToParams = '';
            $fieldList = '';
            $fields = array();
			
            for($field = 0; $field < 20; $field++){
                $fieldNameList[$field] = 'field_'.($field+1);
                $fieldNameListForSQL .= ' field_'.($field+1).',';
                $fieldNameListForSQLToParams .= ' :field_'.($field+1).',';
				$field_validation_type = isset($reportTypeItem[$field]['field_validation_type']) ? $reportTypeItem[$field]['field_validation_type'] : null;
				
                switch($field_validation_type){
                    case 'numeric':
                    case 'integer':
                    case 'integer (positive)':
                    case 'percent':
                    case 'float':
                        if($reportTypeItem[$field]['field_type'] != 'autoIncrement'){
                            $fieldList[$field] = 0;
                        }else{
                            $allIndex = ReportsEntityItems::model()->findAll(array('condition' =>'entity_id = :entity_id' ),array(':entity_id' => $entity_id));
                            $indexCount = count($allIndex);
                            $indexMax = 0;
                            for($idx = 0; $idx < $indexCount; $idx++){
                                (int)$allIndex[$idx]['field_'.$fieldNumber] > (int)$indexMax ? $indexMax = $allIndex[$idx]['field_'.$fieldNumber] : '';
                            }
                            $lastItemIndex = $indexMax + 1 + $incNumber;
                            $fieldList[$field] = $lastItemIndex;
                        }
                        break;
                    case 'date':
                        $fieldList[$field] = LocalTime::currentDate();
                        break;
                    default:
                        $fieldList[$field] = "''";
						break;
                }

				$fields[':field_'.($field+1)] = $fieldList[$field];
                $fieldNumber++;
            }

            $result = CDatabase::init()->customExec(
                'INSERT INTO '.CConfig::get('db.prefix').'reports_entity_items(entity_id, '.$fieldNameListForSQL.' status) VALUES(:entity_id, '.$fieldNameListForSQLToParams.' :status)',
                array_merge($fields,
                    array(
                        ':entity_id' => $entity_id,
                        ':status'    => 1
                    )
                )
            );
			
            $incNumber++;
        }
		
        return $result;
    }

    /**
     * Prepares backend action operations
     * @param mixed $actions
     * @param string $privilegeCategory
     * @param string $redirectPath
     */
    public static function prepareBackendAction($actions = '', $module_code = '', $privilegeCategory = '', $redirectPath = 'backend/dashboard')
    {
        $baseUrl = A::app()->getRequest()->getBaseUrl();
        $backendDirectory = Website::getBackendPath();

        // Block access to this action to non-logged users
        CAuth::handleLogin($backendDirectory.'admin/login');
        if($redirectPath == 'backend/dashboard'){
			$redirectPath = $backendDirectory.$redirectPath;
		}

        // Block access if admin has no privileges to view modules
        if(!Admins::hasPrivilege('modules', 'view')){
            header('location: '.$baseUrl.'reportsIndex/index/code/no-privileges');
            exit;
        }

        $report_category = ReportsTypes::model()->findAll('code = :code', array(':code' => $privilegeCategory));

        if(!Admins::hasPrivilege($privilegeCategory, $actions)){
            header('location: '.$baseUrl.'reportsIndex/index/code/no-privileges');
            exit;
        }

        // Cast actions to array
        if(!is_array($actions)){
            $actions = (array)$actions;
        }

        foreach($actions as $action){
            if(in_array($action, array('view', 'edit', 'approve'))){
                // Block access if admin has no privileges to add/edit modules
                if(!Admins::hasPrivilege('modules', 'edit')){
                    header('location: '.$baseUrl.'reportsIndex/index/code/no-privileges');
                    exit;
                }
            }

            if(in_array($action, array('view'))){
                // Block access if admin has no privileges to delete records
                if(!Admins::hasPrivilege($privilegeCategory, 'view')){
                    header('location: '.$baseUrl.$redirectPath);
                    exit;
                }
            }
        }

        // Set backend mode
        Website::setBackend();
    }
 
	/**
	 * Loads template
	 * @param array $dataReport
	 * @param string $template
	 * @param string $dateFormat
	 * @param string $format
	 * @param int $hideJs
	 * @return HTML
	 */
	public static function loadTemplate($dataReport = array(), $template = '', $dateFormat = '', $format = '', $hideJs = 0)
	{
		$numeric_fields = array('integer', 'integer (positive)', 'numeric', 'float', 'percent');
		// Find the template of a single report row
		preg_match('/<template id="rows">(.*?)<\/template>/si', $template, $matches);
		$rowTemplateOriginal = isset($matches[1]) ? $matches[1] : '';
		$rowTemplatesContent = '';
		foreach($dataReport['reportRows'] as $reportRows){
	
			$rowTemplate = $rowTemplateOriginal;
			for($elem = 1; $elem <= $dataReport['fieldsNameCount']; $elem++){
				$decimalPoints = 2;
				$fieldType = $dataReport['fieldsNameList'][$elem - 1]['field_type'];
				$fieldValid = $dataReport['fieldsNameList'][$elem - 1]['field_validation_type'];
				$replaceContent = '';
				$prependContent = '$';
				$appendContent = '%';
				switch($fieldType){
					case 'imageUpload':
						$src = 'assets/modules/reports/images/'.$reportRows['field_'.$elem];
						if(is_file($src)){
							$replaceContent = '<a class="fancybox" rel="reference_picture" href="'.A::app()->getRequest()->getBaseUrl().$src.'"><img class="small_image" src="'.A::app()->getRequest()->getBaseUrl().$src.'" alt="'.$dataReport['fieldsNameList'][$elem - 1]['field_title'].'"></a>';
						}
						break;
					case 'datetime':
						$replaceContent = (CTime::isEmptyDate($reportRows['field_'.$elem])) ? '' : date($dateFormat, strtotime($reportRows['field_'.$elem]));
						break;
					default:
						if(in_array($fieldValid,$numeric_fields)){
	
							// Remove decimals from percents and autoinc fields
							$fieldValid == 'percent' ? $decimalPoints = 0 : '';
							$fieldType == 'autoIncrement' ? $decimalPoints = 0 : '';
	
							if($format === 'european'){
								// 1,222.33 => '1.222,33'
								$replaceContent = str_replace(',', '', $reportRows['field_'.$elem]);
								$replaceContent = number_format((float)$reportRows['field_'.$elem], $decimalPoints, ',', '.');
							}else{
								$replaceContent = number_format((float)$reportRows['field_'.$elem], $decimalPoints);
							}
						}else{
							$replaceContent = $reportRows['field_'.$elem];
						}
	
						break;
				}

				if($dataReport['accessAbleFields'][$elem-1]['is_active'] == 1 && $dataReport['accessAbleFields'][$elem-1]['internal_use'] == 1){
					$rowTemplate = str_replace('{{field_'.$elem.'}}', $replaceContent, $rowTemplate);
					$rowTemplate = str_replace('{{field_'.$elem.'_prepend}}', $prependContent, $rowTemplate);
					$rowTemplate = str_replace('{{field_'.$elem.'_append}}', $appendContent, $rowTemplate);
				}else{
					$rowTemplate = str_replace('{{field_'.$elem.'}}', '', $rowTemplate);
					$rowTemplate = str_replace('{{field_'.$elem.'_prepend}}', '', $rowTemplate);
					$rowTemplate = str_replace('{{field_'.$elem.'_append}}', '', $rowTemplate);
				}
	
			}
	
			$rowTemplatesContent .= $rowTemplate;
		}
	
		if(isset($dataReport['commentsList'])){
			// Find the template of a single report row
			preg_match('/<template id="comments">(.*?)<\/template>/si', $template, $matches);
			$rowCommentsOriginal = isset($matches[1]) ? $matches[1] : '';
			$rowCommentsContent = '';
	
			foreach($dataReport['commentsList'] as $commentItems)
			{
				$rowComments = $rowCommentsOriginal;
				$src = 'assets/modules/reports/images/';
				$source = array('{{comment_date}}', '{{comment_text}}', '{{image_1}}', '{{image_2}}', '{{image_3}}', '{{image_4}}');
				$displayDate = (CTime::isEmptyDateTime($commentItems['display_date'])) ? '' : date($dateFormat, strtotime($commentItems['display_date']));
				$dist = array(
					$displayDate,
					$commentItems['comment_text'],
					!empty($commentItems['image_1']) ? '<a class="fancybox" rel="reference_picture" href="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_1'].'"><img class="small_image" src="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_1'].'"></a>' : '',
					!empty($commentItems['image_2']) ? '<a class="fancybox" rel="reference_picture" href="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_2'].'"><img class="small_image" src="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_2'].'"></a>' : '',
					!empty($commentItems['image_3']) ? '<a class="fancybox" rel="reference_picture" href="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_3'].'"><img class="small_image" src="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_3'].'"></a>' : '',
					!empty($commentItems['image_4']) ? '<a class="fancybox" rel="reference_picture" href="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_4'].'"><img class="small_image" src="'.A::app()->getRequest()->getBaseUrl().$src.$commentItems['image_4'].'"></a>' : '',
				);
				$rowCommentsContent .= str_replace($source, $dist, $rowComments);
			}
			$template = str_ireplace('{{comments_content}}', $rowCommentsContent, $template);
		}
	
		if(count($dataReport['commentsList']) <= 0){
			// Find the template of a single report row
			preg_match('/<template id="not_found">(.*?)<\/template>/si', $template, $matches);
			$rowNotFoundOriginal = isset($matches[1]) ? $matches[1] : '';
	
			$template = str_ireplace('{{not_found}}', $rowNotFoundOriginal, $template);
		}else{
			$template = str_ireplace('{{not_found}}', '', $template);
		}
	
		if(count($dataReport['reportRows']) <= 0){
			// Find the template of a single report row
			preg_match('/<template id="not_found1">(.*?)<\/template>/si', $template, $matches);
			$rowNotFoundOriginal = isset($matches[1]) ? $matches[1] : '';
	
			$template = str_ireplace('{{not_found1}}', $rowNotFoundOriginal, $template);
		}else{
			$template = str_ireplace('{{not_found1}}', '', $template);
		}
	
		$template = str_ireplace('{{rows_content}}', $rowTemplatesContent, $template);
	
		$template = str_ireplace('{{client_name}}', $dataReport['projectData']['client_name'], $template);
		$template = str_ireplace('{{project_created}}', $dataReport['projectData']['project_created'], $template);
		$template = str_ireplace('{{client_address}}', $dataReport['projectData']['client_address'], $template);
		$template = str_ireplace('{{client_email}}', $dataReport['projectData']['client_email'], $template);
		$template = str_ireplace('{{client_phone}}', $dataReport['projectData']['client_phone'], $template);
		$template = str_ireplace('{{project_manage_price}}', $dataReport['projectData']['project_manage_price'], $template);
		$template = str_ireplace('{{project_design_price}}', $dataReport['projectData']['project_design_price'], $template);
		$template = str_ireplace('{{project_price}}', $dataReport['projectData']['project_price'], $template);
		$template = str_ireplace('{{logo_path}}', $dataReport['projectData']['logo_path'], $template);
		$template = str_ireplace('{{logo2_path}}', $dataReport['projectData']['logo2_path'], $template);
		$template = str_ireplace('{{report_name}}', $dataReport['projectData']['report_name'], $template);
		$template = str_ireplace('{{project_name}}', $dataReport['projectData']['project_name'], $template);
	
		$rowTemplatesJS = "
	<script src='".A::app()->getRequest()->getBaseUrl()."assets/vendors/jquery/jquery.js'></script>
	<link rel='stylesheet' href='".A::app()->getRequest()->getBaseUrl()."assets/vendors/fancybox/jquery.fancybox.css' />
	<script src='".A::app()->getRequest()->getBaseUrl()."assets/vendors/fancybox/jquery.mousewheel.pack.js'></script>
	<script src='".A::app()->getRequest()->getBaseUrl()."assets/vendors/fancybox/jquery.fancybox.pack.js'></script>
	
		<script>
			$('.fancybox').fancybox({
				'opacity'		: true,
				'overlayShow'	: false,
				'overlayColor'	: '#000',
				'overlayOpacity': 0.5,
				'titlePosition'	: 'inside',
				'cyclic'        : true,
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'fade'
			});
		</script>
	
	";
		// Check to display js scripts
		if(empty($hideJs)){
			$template = str_ireplace('{{fancybox_scripts}}', $rowTemplatesJS, $template);
		}else{
			$template = str_ireplace('{{fancybox_scripts}}', '', $template);
		}
	
		return $template;
	}
		
}