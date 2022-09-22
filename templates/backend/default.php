<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?= CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?= CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?= CConfig::get('name').' v'.CConfig::get('version'); ?>">
	<!-- don't move it -->
    <base href="<?= A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?= CHtml::encode($this->_pageTitle); ?></title>
	<link rel="shortcut icon" href="favicon.ico" />

    <?= CHtml::cssFile('templates/backend/css/style.css'); ?>
    <?php if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/backend/css/style.rtl.css'); ?>

    <!-- jQuery files -->
	<?//= CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?//= CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?= CHtml::scriptFile('assets/vendors/jquery/jquery.js'); ?>

	<?php
		// <!-- jQuery ui files -->
		// Use registerScriptFile() because we want to prevent loading jquery-ui.min.js twice (also used in framework widgets)
		A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery-ui.min.js',2);
		//echo CHtml::scriptFile('assets/vendors/jquery/jquery-ui.min.js');
	?>
    
	<!-- Browser.mobile files -->
	<?= CHtml::scriptFile('assets/vendors/jquery/jquery.browser.js'); ?>

    <!-- Tooltip files -->
    <?= CHtml::scriptFile('assets/vendors/tooltip/jquery.tipTip.min.js'); ?>
    <?= CHtml::cssFile('assets/vendors/tooltip/tipTip.css'); ?>

	<!-- Chosen files -->
	<?= CHtml::scriptFile('assets/vendors/chosen/chosen.jquery.min.js'); ?>
	<?= CHtml::cssFile('assets/vendors/chosen/chosen.min.css'); ?>

    <!-- Site JS main files -->
	<?= CHtml::script('var cookiePath = "'.A::app()->getRequest()->getBasePath().'";'); ?>
    <?= CHtml::scriptFile('templates/backend/js/main.js'); ?>
	
</head>
<?php
	/* Define special class for body when left menu is collapsed */
	$bodyClass = A::app()->getCookie()->get('isCollapsed') == 'true' ? ' class="collapsed"' : '';
?>
<body<?= $bodyClass; ?>>
<?php
    if(!CAuth::isLoggedInAsAdmin()):
        echo '<div class="back-to-site"><a href="'.Website::getDefaultPage().'">'.A::t('app', 'Back to Site').'</a></div>';            
        echo A::app()->view->getContent();
    else:
?>         
    <!-- HEADER --> 
    <div id="head">
        <div class="left">
            <?php
				$siteTitle = (A::app()->view->siteTitle != '') ? ' / '.CHtml::encode(A::app()->view->siteTitle) : '';
                echo CHtml::link(A::t('app', 'Admin Panel Title').$siteTitle, (CAuth::isLoggedIn() ? 'backend/index' : Website::getDefaultPage()), array('class'=>'header-title'));
            ?>
        </div>
        <div class="right">
            <?php
                if(CAuth::isLoggedInAsAdmin()){
                    // Draw backend menu
                    echo BackendMenu::drawProfileMenu($this->_activeMenu);	
                }else{
                    echo '<a href="backend/login">'.A::t('app', 'Admin Login').'</a>';
                }                
            ?>
        </div>
    </div>

    <!-- SIDEBAR -->
    <div id="sidebar">
        <div id="top-line">            
            <a href="javascript:void(0)" id="menuopen"><?= A::t('app', 'Open'); ?> <label>&#9660;</label></a>
            <a href="javascript:void(0)" id="menuclose"><?= A::t('app', 'Close'); ?> <label>&#9650;</label></a>            
            <a href="javascript:void(0)" id="menucollapse" data-direction="<?= A::app()->getLanguage('direction'); ?>"><?= ((A::app()->getLanguage('direction') == 'rtl') ? '&#9654;' : '&#9664;'); ?></a>
        </div>
        <?= BackendMenu::drawSideMenu($this->_activeMenu); ?>
    </div>
                
    <!-- CONTENT --> 
    <div id="content">
        <?php
            CWidget::create('CBreadCrumbs', array(
                'links' => $this->_breadCrumbs,
                'separator' => '&nbsp;/&nbsp;',
				'return'=>false
            ));
		?>
		<?= A::app()->view->getContent(); ?>
    </div>    
<?php endif; ?>
</body>
</html>