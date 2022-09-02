<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Cron Jobs Settings')));
	
	$this->_activeMenu = 'settings/';
    $this->_breadCrumbs = array(
        array('label'=>A::t('app', 'General'), 'url'=>'backend/'),
        array('label'=>A::t('app', 'Site Settings'), 'url'=>'settings/general'),
		array('label'=>A::t('app', 'Cron Jobs Settings'))
    );    
?>
    
<h1><?= A::t('app', 'Cron Jobs Settings'); ?></h1>

<div class="bloc">

	<?= $tabs; ?>

	<div class="content">
	<?= $actionMessage; ?>
	
		<div class="left-side" id="left-cronjobs">
		<?php 		
			$cronRunLastTime = strtotime($settings->cron_run_last_time) > 0 ? CLocale::date($settings->datetime_format, $settings->cron_run_last_time) : A::t('app', 'Never');        
			$selectedValue = $settings->cron_type;
         
			echo CWidget::create('CFormView', array(
				'action'=>'settings/cron',
				'method'=>'post',				
				'htmlOptions'=>array(
					'name'=>'frmCronJobs',
					'autoGenerateId'=>true
				),
        		'fields'=>array(
					'act'     =>array('type'=>'hidden', 'value'=>'send'),
					'cronType'=>array('type'=>'radioButtonList', 'checked'=>$settings->cron_type, 'title'=>A::t('app', 'Run Cron'), 'tooltip'=>A::t('app', 'Run Cron Tooltip'), 'data'=>$cronTypesList, 'mandatoryStar'=>true, 'htmlOptions'=>array('onchange'=>'showRunPeriod(this.value);', 'class'=>'css-radiobutton', 'labelOptions'=>array('class'=>'css-radiobutton-label'))),
					'cronRunPeriodValue'=>array('type'=>'select', 'value'=>$settings->cron_run_period_value, 'title'=>A::t('app', 'Run Every'), 'tooltip'=>A::t('app', 'Run Every Tooltip'), 'data'=>$numbersList, 'htmlOptions'=>array()),
					'cronRunPeriod'=>array('type'=>'select', 'value'=>$settings->cron_run_period, 'title'=>' ', 'data'=>$cronRunPeriodsList, 'htmlOptions'=>array()),
					'cronRunLastTime'=>array('type'=>'label', 'value'=>$cronRunLastTime, 'title'=>A::t('app', 'Last Run')),
				),
				'buttons'=> Admins::hasPrivilege('site_settings', 'edit') ? 
					array(
						'submit'=>array('type'=>'submit', 'value'=>A::t('app', 'Update')))
					: array(),
				'events'=>array(
					'focus'=>array('field'=>$errorField)
				),
				'return'=>true,
			));
		?>        
	    </div>
	
	    <div class="central-part" id="right-cronjobs">
	    	<?= A::t('app', 'Cron Jobs Notice');?>
	    	<br /><br />
	    	<?= A::t('app', 'Cron Jobs Batch Command');?>
			<b>php &#36;HOME/public_html/cron.php &gt;/dev/null 2&gt;&1</b><br /><br />
			<?= A::t('app', 'Cron Jobs htaccess Block');?>
			<br /><br />

<pre>
&lt;Files "cron.php"&gt;
   Order Deny,Allow
   Deny from all
   Allow from localhost
   Allow from 127.0.0.1
   Allow from xx.xx.xx.xx &lt;-- <?= A::t('app', 'add here your IP address (allowed)');?>
   
&lt;/Files&gt;
</pre>
	    </div>
		
		<div class="clear"></div>	
	</div>
</div>   

<?php
	A::app()->getClientScript()->registerScript(
		'cron-toggle',
		'function showRunPeriod(val){
			if(val == "non-batch"){
				$("#frmCronJobs_row_1,#frmCronJobs_row_2,#frmCronJobs_row_3").show();
				$(".chosen-container-single").css({"min-width":"90px", "width":"auto"});
			}else if(val == "batch"){
				$("#frmCronJobs_row_1,#frmCronJobs_row_2").hide();
				$("#frmCronJobs_row_3").show();			
			}else if(val == "stop"){
				$("#frmCronJobs_row_1,#frmCronJobs_row_2,#frmCronJobs_row_3").hide();
			}
		};',
		0
	);
	
	A::app()->getClientScript()->registerScript(
		'cron-restore',
		'showRunPeriod("'.$selectedValue.'");',
		2
	);
?>
	
