<?php
/**
 * CGridView widget helper class file
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2013 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * init                                                 additionalParams 
 * 
 */	  

class CGridView
{
    const NL = "\n";

    /**
     * Draws grid view
     * @param array $params
     *
     * Notes:
     *   - to disable any field or button use: 'disabled'=>true
     *   
     * Usage:
     *  echo CWidget::create('CGridView', array(
     *    'model'=>'tableName',
     *    'actionPath'=>'controller/action',
     *    'condition'=>CConfig::get('db.prefix').'countries.id <= 30',
     *    'defaultOrder'=>array('field_1'=>'DESC', 'field_2'=>'ASC' [,...]),
     *    'passParameters'=>false,
	 *    'pagination'=>array('enable'=>true, 'pageSize'=>10),
	 *    'sorting'=>true,
     *    'filters'=>array(
     *    	 'field_1' => array('title'=>'Field 1', 'type'=>'textbox', 'operator'=>'=', 'width'=>'', 'maxLength'=>''),
     *    	 'field_2' => array('title'=>'Field 2', 'type'=>'enum', 'operator'=>'=', 'width'=>'', 'source'=>array('0'=>'No', '1'=>'Yes')),
     *    	 'field_3' => array('title'=>'Field 3', 'type'=>'datetime', 'operator'=>'=', 'width'=>'80px', 'maxLength'=>'', 'format'=>''),
     *    ),
	 *    'fields'=>array(
	 *       'field_1' => array('title'=>'Field 1', 'type'=>'label', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'definedValues'=>array(), 'format'=>''),
	 *       'field_2' => array('title'=>'Field 2', 'type'=>'enum', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>true, 'source'=>array('0'=>'No', '1'=>'Yes')),
	 *       'field_3' => array('title'=>'Field 3', 'type'=>'link', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'linkUrl'=>'path/to/param/{id}', 'linkText'=>''),
	 *       'field_4' => array('title'=>'Field 4', 'type'=>'image', 'align'=>'', 'width'=>'', 'class'=>'center', 'headerClass'=>'center', 'isSortable'=>false, 'imagePath'=>'images/flags/', 'defaultImage'=>'', 'imageWidth'=>'16px', 'imageHeight'=>'16px', 'alt'=>''),
	 *       'field_5' => array('title'=>'Field 5', 'type'=>'concat', 'align'=>'', 'width'=>'', 'class'=>'left', 'headerClass'=>'left', 'isSortable'=>true, 'concatFields'=>array('first_name', 'last_name'), 'concatSeparator'=>', ',),
	 *    ),
	 *    'actions'=>array(
     *    	 'edit'    => array('link'=>'locations/edit/{id}/page/{page}', 'imagePath'=>'templates/backend/images/edit.png', 'title'=>'Edit this record'),
     *    	 'delete'  => array('link'=>'locations/delete/{id}/page/{page}', 'imagePath'=>'templates/backend/images/delete.png', 'title'=>'Delete this record', 'onDeleteAlert'=>true),
     *    ),
	 *    'return'=>true,
     *  ));
    */
    public static function init($params = array())
    {       
        $output 	   = '';
        $model 		   = isset($params['model']) ? $params['model'] : '';
		$actionPath    = isset($params['actionPath']) ? $params['actionPath'] : '';
		$condition 	   = isset($params['condition']) ? $params['condition'] : '';
		$defaultOrder  = isset($params['defaultOrder']) ? $params['defaultOrder'] : '';
		$passParameters = isset($params['passParameters']) ? (bool)$params['passParameters'] : false;
        $return 	   = isset($params['return']) ? (bool)$params['return'] : true;
		$fields 	   = isset($params['fields']) ? $params['fields'] : array();
		$filters	   = isset($params['filters']) ? $params['filters'] : array();
		$actions 	   = isset($params['actions']) ? $params['actions'] : array();
		$activeActions = is_array($actions) ? count($actions) : 0;

		// remove disabled actions
		if(is_array($actions)){
			foreach($actions as $key => $val){
				if(isset($val['disabled']) && (bool)$val['disabled'] === true) unset($actions[$key]);
			}
			$activeActions = count($actions);
		}
		
		// prepare sorting variables
		// ---------------------------------------
		$sorting = isset($params['sorting']) ? (bool)$params['sorting'] : false;
		$sortBy = $sortDir = $sortUrl = '';
		$orderClause = '';
		if($sorting){
			$sortBy = A::app()->getRequest()->getQuery('sort_by', 'dbfield', '');		
			$sortDir = (strtolower(A::app()->getRequest()->getQuery('sort_dir', 'alpha', '')) == 'desc') ? 'desc' : 'asc';		
		}	
		if($sorting && !empty($sortBy)){
			$orderClause = $sortBy.' '.$sortDir;
		}else if(is_array($defaultOrder)){
			foreach($defaultOrder as $oField => $oFieldType){
				$orderClause .= (!empty($orderClause) ? ', ' : '').$oField.' '.$oFieldType;
			}					
		}
		
		// prepare filter variables
		// ---------------------------------------
		$whereClause = (!empty($condition)) ? $condition : '';
		$filterUrl = '';		
		if(is_array($filters) && !empty($filters)){
			$output .= CHtml::openTag('div', array('class'=>'filtering-wrapper')).self::NL;
			$output .= CHtml::openForm($actionPath, 'get', array()).self::NL;
			foreach($filters as $fKey => $fValue){
				$title = isset($fValue['title']) ? $fValue['title'] : '';				
				$type = isset($fValue['type']) ? $fValue['type'] : '';
				$width = (isset($fValue['width']) && CValidator::isHtmlSize($fValue['width'])) ? 'width:'.$fValue['width'].';' : '';
				$maxLength = isset($fValue['maxLength']) && !empty($fValue['maxLength']) ? (int)$fValue['maxLength'] : '255';
				$fieldOperator = isset($fValue['operator']) ? $fValue['operator'] : '';				
				$fieldValue = CHtml::decode(A::app()->getRequest()->getQuery($fKey));
				$output .= $title.': ';
				switch($type){
					case 'enum':
						$source = isset($fValue['source']) ? $fValue['source'] : array();
						$sourceCount = count($source);
						if($sourceCount > 1 || ($sourceCount == 1 && $source[0] != '')){
							$output .= (count($source)) ? CHtml::dropDownList($fKey, $fieldValue, $source, array('style'=>$width)) : 'no';	
						}else{
							$output .= A::t('core', 'none');
						}
						$output .= '&nbsp;'.self::NL;
						break;
					case 'datetime':
						$format = isset($fieldInfo['format']) ? $fieldInfo['format'] : 'yy-mm-dd';
						$output .= CHtml::textField($fKey, $fieldValue, array('maxlength'=>$maxLength, 'style'=>$width));
						A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
						/* formats: dd/mm/yy | d M, y | mm/dd/yy  | yy-mm-dd  | */
						A::app()->getClientScript()->registerScript(
							'datepicker',
							'$("#'.$fKey.'").datepicker({
								showOn: "button",
								buttonImage: "js/vendors/jquery/images/calendar.png",
								buttonImageOnly: true,
								showWeek: false,
								firstDay: 1,					  
								dateFormat: "'.$format.'",
								changeMonth: true,
								changeYear: true,
								appendText : ""
							});'
						);
						break;
					case 'textbox':
					default:
						$output .= CHtml::textField($fKey, CHtml::encode($fieldValue), array('maxlength'=>$maxLength, 'style'=>$width)).self::NL;
						break;
				}
				if($fieldValue !== '') $filterUrl .= (!empty($filterUrl) ? '&' : '').$fKey.'='.$fieldValue;
				if($fieldValue !== ''){
					$escapedFieldValue = mysql_real_escape_string($fieldValue);
					$whereClause .= !empty($whereClause) ? ' AND ' : '';
					$whereClause .= $fKey.' ';
					switch($fieldOperator){
						case 'like':
							$whereClause .= 'like \''.$escapedFieldValue.'\''; break;
						case 'not like':
							$whereClause .= 'not like \''.$escapedFieldValue.'\''; break;
						case '%like':
							$whereClause .= 'like \'%'.$escapedFieldValue.'\''; break;
						case 'like%':
							$whereClause .= 'like \''.$escapedFieldValue.'%\''; break;
						case '%like%':
							$whereClause .= 'like \'%'.$escapedFieldValue.'%\''; break;
						case '!=':
						case '<>':	
							$whereClause .= '!= \''.$escapedFieldValue.'\''; break;
						case '>':	
							$whereClause .= '> \''.$escapedFieldValue.'\''; break;
						case '>=':	
							$whereClause .= '>= \''.$escapedFieldValue.'\''; break;
						case '<':	
							$whereClause .= '< \''.$escapedFieldValue.'\''; break;
						case '<=':	
							$whereClause .= '<= \''.$escapedFieldValue.'\''; break;
						case '=':
						default:
							$whereClause .= '= \''.$escapedFieldValue.'\'';	break;
					}
				} 
			}
			$output .= CHtml::openTag('div', array('class'=>'buttons-wrapper')).self::NL;
			if(A::app()->getRequest()->getQuery('but_filter')){
				$filterUrl .= (!empty($filterUrl) ? '&' : '').'but_filter=true';
				$output .= CHtml::button(A::t('core', 'Cancel'), array('name'=>'', 'class'=>'button white', 'onclick'=>'$(location).attr(\'href\',\''.$actionPath.'\');')).self::NL;
			}
			$output .= CHtml::submitButton(A::t('core', 'Filter'), array('name'=>'but_filter')).self::NL;
			$output .= CHtml::closeTag('div').self::NL;
			$output .= CHtml::closeForm().self::NL;
			$output .= CHtml::closeTag('div').self::NL;
			$filterUrl = CHtml::encode($filterUrl);
		}

		// prepare pagination variables
		// ---------------------------------------
		$pagination = isset($params['pagination']['enable']) ? (bool)$params['pagination']['enable'] : '';
		$pageSize = isset($params['pagination']['pageSize']) ? abs((int)$params['pagination']['pageSize']) : '10';
		$totalRecords = $totalPageRecords = 0;
		$currentPage = '';
		$objModel = call_user_func_array($model.'::model', array());
		if($pagination){			
			$currentPage = A::app()->getRequest()->getQuery('page', 'integer', 1);
			$totalRecords = $objModel->count($whereClause);			
			if($currentPage){				
				$records = $objModel->findAll(array(
					'condition'=>$whereClause,
					'limit'=>(($currentPage - 1) * $pageSize).', '.$pageSize,
					'order'=>$orderClause
				));
				$totalPageRecords = is_array($records) ? count($records) : 0;
			}
			if(!$totalPageRecords || !$currentPage){
				if(A::app()->getRequest()->getQuery('but_filter')){
					$output .= CWidget::create('CMessage', array('warning', A::t('core', 'No records found or incorrect parameters passed')));
				}else{
					$output .= CWidget::create('CMessage', array('warning', A::t('core', 'No records found')));					
				}
			}
		}else{
			$records = $objModel->findAll(array(
				'condition'=>$whereClause,
				'order'=>$orderClause
			));
			$totalPageRecords = is_array($records) ? count($records) : 0;
			if(!$totalPageRecords){
				$output .= CWidget::create('CMessage', array('error', A::t('core', 'No records found')));
			}
		}

		// draw rows
		// ---------------------------------------
        if($totalPageRecords > 0){
			
			// remove disabled fields
			foreach($fields as $key => $val){
				if(isset($val['disabled']) && (bool)$val['disabled'] === true) unset($fields[$key]);
			}
			
			// draw headers
            $output .= CHtml::openTag('table').self::NL;
            $output .= CHtml::openTag('thead').self::NL;
            $output .= CHtml::openTag('tr').self::NL;
				foreach($fields as $key => $val){
					$title = isset($val['title']) ? $val['title'] : '';
					$headerClass = isset($val['headerClass']) ? $val['headerClass'] : '';
					$isSortable = isset($val['isSortable']) ? (bool)$val['isSortable'] : true;
					
					// prepare style attributes
					$width = (isset($val['width']) && CValidator::isHtmlSize($val['width'])) ? 'width:'.$val['width'].';' : '';
					$align = (isset($val['align']) && CValidator::isAlignment($val['align'])) ? 'text-align:'.$val['align'].';' : '';					
					$style = $width.$align;
					
					if($sorting && $isSortable){
						$colSortDir = (($sortBy != $key) ? 'asc' : (($sortDir == 'asc') ? 'desc' : 'asc'));
						$sortImg = ($sortBy == $key) ? ' '. CHtml::tag('span', array('class'=>'sort-arrow'), (($colSortDir == 'asc') ? '&#9660;' : '&#9650;')) : '';
						if($sortBy == $key) $sortUrl = 'sort_by='.$key.'&sort_dir='.$sortDir;
						$linkUrl = $actionPath.'?'.'sort_by='.$key.'&sort_dir='.$colSortDir;
						$linkUrl .= !empty($currentPage) ? '&page='.$currentPage : '';
						$linkUrl .= !empty($filterUrl) ? '&'.$filterUrl : '';
						$title = CHtml::link($title.$sortImg, $linkUrl);
					}
					$output .= CHtml::tag('th', array('class'=>$headerClass, 'style'=>$style), $title).self::NL;
				}
				if($activeActions > 0){
					$output .= CHtml::tag('th', array('class'=>'actions'), A::t('core', 'Actions')).self::NL;
				}
                $output .= CHtml::closeTag('tr').self::NL;;
            $output .= CHtml::closeTag('thead').self::NL;
			
			// draw content 
			$output .= CHtml::openTag('tbody');			
			for($i = 0; $i < $totalPageRecords; $i++){
				$output .= CHtml::openTag('tr').self::NL;
				$id = (isset($records[$i]['id'])) ? $records[$i]['id'] : '';
				foreach($fields as $key => $val){
					$style = (isset($val['align']) && CValidator::isAlignment($val['align'])) ? 'text-align:'.$val['align'].';' : '';
					$class = isset($val['class']) ? $val['class'] : '';
					$type = isset($val['type']) ? $val['type'] : '';
					$title = isset($val['title']) ? $val['title'] : '';
					$fieldValue = (isset($records[$i][$key])) ? $records[$i][$key] : $key;
					$output .= CHtml::openTag('td', array('class'=>$class, 'style'=>$style));
                    switch($type){
                        case 'enum':
							$source = isset($val['source']) ? $val['source'] : '';							
							$output .= isset($source[$fieldValue]) ? $source[$fieldValue] : '';	
                            break;
						case 'image':
							$imagePath = isset($val['imagePath']) ? $val['imagePath'] : '';
							$defaultImage = isset($val['defaultImage']) ? $val['defaultImage'] : '';
							$alt = isset($val['alt']) ? $val['alt'] : '';
							$htmlOptions = array();
							if(isset($val['imageWidth']) && CValidator::isHtmlSize($val['imageWidth'])) $htmlOptions['width'] = $val['imageWidth'];
							if(isset($val['imageHeight']) && CValidator::isHtmlSize($val['imageHeight'])) $htmlOptions['height'] = $val['imageHeight'];
							if((!$fieldValue || !file_exists($imagePath.$fieldValue)) && !empty($defaultImage)) $fieldValue = $defaultImage;
							$output .= CHtml::image($imagePath.$fieldValue, $alt, $htmlOptions).self::NL;
							break;
						case 'link':
							$linkUrl = isset($val['linkUrl']) ? str_ireplace('{id}', $id, $val['linkUrl']) : '#';
							$linkText = isset($val['linkText']) ? $val['linkText'] : $title;
							$output .= CHtml::link($linkText, $linkUrl);
							break;
						case 'concat':
							$concatFields = isset($val['concatFields']) ? $val['concatFields'] : '';
							$concatSeparator = isset($val['concatSeparator']) ? $val['concatSeparator'] : ' ';
							$concatResult = '';
							if(is_array($concatFields)){
								foreach($concatFields as $cfKey){
									if(!empty($concatResult)) $concatResult .= $concatSeparator;
									$concatResult .= $records[$i][$cfKey];
								}
							}
							$output .= $concatResult;
							break;							
						case 'label':
						default:
							$definedValues = isset($val['definedValues']) ? $val['definedValues'] : '';
							$format = isset($val['format']) ? $val['format'] : '';
							if(is_array($definedValues) && isset($definedValues[$fieldValue])){
								$fieldValue = $definedValues[$fieldValue];
							}else if($format != ''){
								$fieldValue = date($format, strtotime($fieldValue));
							}
							$output .= $fieldValue;								
							break;
					}
					$output .= CHtml::closeTag('td').self::NL;
				}
				if($activeActions > 0){
					$output .= CHtml::openTag('td', array('class'=>'actions'));
					foreach($actions as $aKey => $aVal){
						$htmlOptions = array('class'=>'tooltip-link');
						if(isset($aVal['title'])) $htmlOptions['title'] = $aVal['title'];
						if(isset($aVal['onDeleteAlert']) && $aVal['onDeleteAlert'] == true){
							A::app()->getClientScript()->registerScript(
								'delete-record',
								'function onDeleteRecord(){return confirm("'.A::t('core', 'Are you sure you want to delete this record?').'");}',
								2
							);
							$htmlOptions['onclick'] = 'return onDeleteRecord();';
						}
						$imagePath = isset($aVal['imagePath']) ? $aVal['imagePath'] : '';
						$linkUrl = isset($aVal['link']) ? str_ireplace('{id}', $id, $aVal['link']) : '#';
						// add additional parameters if allowed
						if($linkUrl != '#') $linkUrl .= self::additionalParams($passParameters);
						$linkLabel = (!empty($imagePath) ? '<img src="'.$imagePath.'" alt="'.$aKey.'" />' : $aKey);
						
						$output .= CHtml::link($linkLabel, $linkUrl, $htmlOptions).' ';
					}
					$output .= CHtml::closeTag('td').self::NL;
				}
				$output .= CHtml::closeTag('tr').self::NL;
			}                
                
            $output .= CHtml::closeTag('tbody').self::NL;
			$output .= CHtml::closeTag('table').self::NL;
			
			// draw pagination
			if($pagination){			
				$paginationUrl = $actionPath;
				$paginationUrl .= !empty($sortUrl) ? '?'.$sortUrl : '';
				$paginationUrl .= !empty($filterUrl) ? (empty($sortUrl) ? '?' : '&').$filterUrl : '';				
				$output .= CWidget::create('CPagination', array(
					'actionPath'   => $paginationUrl,
					'currentPage'  => $currentPage,
					'pageSize'     => $pageSize,
					'totalRecords' => $totalRecords,
					'linkType' => 0,
					'paginationType' => 'fullNumbers'
				));
			}
        }
		
		if($return) return $output;
        else echo $output;
	}

    /**
	 * Prepare additional parameters that will be passed
	 * @param bool $allow
     */
	private static function additionalParams($allow = false)
    {
		$output = '';
		if($allow){
			$page = A::app()->getRequest()->getQuery('page', 'integer', 1);
			$sortBy = A::app()->getRequest()->getQuery('sort_by', 'string');
			$sortDir = A::app()->getRequest()->getQuery('sort_dir', 'string');				
			$output .= ($sortBy) ? '/sort_by/'.$sortBy : '';
			$output .= ($sortDir) ? '/sort_dir/'.$sortDir : '';
			$output .= ($page) ? '/page/'.$page : '';
		}
		return $output;
	}
 
}