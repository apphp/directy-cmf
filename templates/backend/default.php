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
	<?php echo CHtml::scriptFile('js/vendors/jquery/jquery-ui.min.js'); ?>
    
    <!-- tooltip files -->
    <?php echo CHtml::scriptFile('js/vendors/tooltip/jquery.tipTip.min.js'); ?>
    <?php echo CHtml::cssFile('js/vendors/tooltip/tipTip.css'); ?>

    <!-- site js main files -->
	<?php echo CHtml::script('var cookiePath = "'.A::app()->getRequest()->getBasePath().'";'); ?>
    <?php echo CHtml::scriptFile('templates/backend/js/main.js'); ?>
	
</head>
<body>
<?php
    if(!CAuth::isLoggedInAsAdmin()){            
        echo '<div class="back-to-site"><a href="index/">'.A::t('app', 'Back to Site').'</a></div>';            
        echo A::app()->view->getContent();
    }else{
?>         
    <!-- HEADER --> 
    <div id="head">
        <div class="left">
            <?php
                echo CHtml::link(A::t('app', 'Admin Panel Title'), (CAuth::isLoggedIn() ? 'backend/index' :  'index/index'), array('class'=>'header-title'));
            ?>
        </div>
        <div class="right">
            <?php
                if(CAuth::isLoggedInAsAdmin()){
                    // draw backend menu
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
			// draw backend menu
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