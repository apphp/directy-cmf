<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?php echo CConfig::get('name').' v'.CConfig::get('version'); ?>">
	<!-- don't move it -->
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>    
	<link rel="shortcut icon" href="images/apphp.ico" />    

    <?php echo CHtml::cssFile('templates/backend/css/style.css'); ?>
    <?php if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/backend/css/style.rtl.css'); ?>

    <!-- jquery files -->
	<?php //echo CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?php //echo CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?php echo CHtml::scriptFile('js/vendors/jquery/jquery.js'); ?>

	<?php
		// <!-- jquery ui files -->
		// Use registerScriptFile() because we want to prevent loading jquery-ui.min.js twice (also used in framework widgets)
		A::app()->getClientScript()->registerScriptFile('js/vendors/jquery/jquery-ui.min.js',2);
		//echo CHtml::scriptFile('js/vendors/jquery/jquery-ui.min.js');
	?>
    
	<!-- browser.mobile files -->
	<?php echo CHtml::scriptFile('js/vendors/jquery/jquery.browser.js'); ?>

    <!-- tooltip files -->
    <?php echo CHtml::scriptFile('js/vendors/tooltip/jquery.tipTip.min.js'); ?>
    <?php echo CHtml::cssFile('js/vendors/tooltip/tipTip.css'); ?>

	<!-- chosen files -->
	<?php echo CHtml::scriptFile('js/vendors/chosen/chosen.jquery.min.js'); ?>
	<?php echo CHtml::cssFile('js/vendors/chosen/chosen.min.css'); ?>

    <!-- site js main files -->
	<?php echo CHtml::script('var cookiePath = "'.A::app()->getRequest()->getBasePath().'";'); ?>
    <?php echo CHtml::scriptFile('templates/backend/js/main.js'); ?>
	
</head>
<?php
	/* Define special class for body when left menu is collapsed */
	$bodyClass = A::app()->getCookie()->get('isCollapsed') == 'true' ? ' class="collapsed"' : '';
?>
<body<?php echo $bodyClass; ?>>
<?php
    if(!CAuth::isLoggedInAsAdmin()){            
        echo '<div class="back-to-site"><a href="'.Website::getDefaultPage().'">'.A::t('app', 'Back to Site').'</a></div>';            
        echo A::app()->view->getContent();
    }else{
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
            <a href="javascript:void(0)" id="menuopen"><?php echo A::t('app', 'Open'); ?> <label>&#9660;</label></a>
            <a href="javascript:void(0)" id="menuclose"><?php echo A::t('app', 'Close'); ?> <label>&#9650;</label></a>            
            <a href="javascript:void(0)" id="menucollapse" data-direction="<?php echo A::app()->getLanguage('direction'); ?>"><?php echo ((A::app()->getLanguage('direction') == 'rtl') ? '&#9654;' : '&#9664;'); ?></a>
        </div>
        <?php
			// Draw backend menu
			echo BackendMenu::drawSideMenu($this->_activeMenu);	
        ?>
    </div>
                
    <!-- CONTENT --> 
    <div id="content">
        <?php
            CWidget::create('CBreadCrumbs', array(
                'links' => $this->_breadCrumbs,
                'separator' => '&nbsp;/&nbsp;',
				'return'=>false
            ));
            echo A::app()->view->getContent();
        ?>
    </div>    
<?php         
    }
?>
</body>
</html>