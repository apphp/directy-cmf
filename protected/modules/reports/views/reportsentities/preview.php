<?php
	Website::setMetaTags(array('title'=>A::t('reports', 'Report Preview')));
	
	$this->_activeMenu = 'reportsProjects/manage';
	$this->_breadCrumbs = array(
		array('label'=>A::t('reports', 'Modules'), 'url'=>$backendPath.'modules/'),
		array('label'=>A::t('reports', 'Reports'), 'url'=>$backendPath.'modules/settings/code/reports'),
		array('label'=>A::t('reports', 'Projects'), 'url'=>'reportsProjects/manage'),
		array('label'=>A::t('reports', 'Report Preview'), 'url'=>'reportsEntities/manage'),
	);
	
	$isLandScape = ModulesSettings::model()->param('reports', 'report_pdf_type');

	$template = '';
	if(!file_exists(APPHP_PATH.'/protected/modules/reports/templates/'.$dataReport[0]['templateFile'])){
		echo A::t('reports', 'Template for this report not found. Check if the following file exists').': '.$dataReport[0]['missingFilename'];		
	}else{
		// In future you can change template depend settings LandScape or Portrait template
		///if($isLandScape == 'LandScape'){...}
		$template = file_get_contents(APPHP_PATH.'/protected/modules/reports/templates/'.$dataReport[0]['templateFile']);
	}

	if(isset($relatedReport)){
		$templateRel = '';
		if(!file_exists(APPHP_PATH.'/protected/modules/reports/templates/'.$dataReport[1]['templateFile'])){
			echo A::t('reports', 'Template for this report not found. Check if the following file exists').': '.$dataReport[1]['missingFilename'];
		}else{
			// In future you can change template depend settings LandScape or Portrait template
			///if($isLandScape == 'LandScape'){...}
			$templateRel = file_get_contents(APPHP_PATH.'/protected/modules/reports/templates/'.$dataReport[1]['templateFile']);
		}
	}
	
	// Fetch site settings info
	$settings = Bootstrap::init()->getSettings();
	$dateTimeFormat = $settings->datetime_format;
	$dateFormat = $settings->date_format;
	$format = $settings->number_format;
	
	// Remove body (fix for PDF)
	$template = str_ireplace(array('<body>', '</body>'), '', $template);
	
	$templateMain = \Modules\Reports\Components\ReportsProjectsComponent::loadTemplate($dataReport[0], $template, $dateFormat, $format, $hideJs);
	// Replace {{currency}} with default currency
	$templateMain = str_replace('{{currency}}', A::app()->getCurrency('symbol'), $templateMain);
	
	if(isset($relatedReport) && $hideJs == 0){
		$relatedReportData = \Modules\Reports\Components\ReportsProjectsComponent::LoadTemplate($dataReport[1], $templateRel, $dateFormat, $format, $hideJs);
		// Erase unnecessary tags
		$relatedReportData = str_ireplace(array('<html>','</html>', '<body>', '</body>', '<meta charset="UTF-8" />'), '',$relatedReportData);
		$templateMain = preg_replace('/<\/html>/i', $relatedReportData.'</html>', $templateMain);
	}
	
	echo $templateMain;

	