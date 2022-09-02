<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?= CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?= CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?= CConfig::get('name').' v'.CConfig::get('version'); ?>" />
	<!-- don't move it -->
    <base href="<?= A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?= CHtml::encode($this->_pageTitle); ?></title>    
	<link rel="shortcut icon" href="images/apphp.ico" />    

    <?= CHtml::cssFile('templates/default/css/style.css'); ?>
    <?php if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/default/css/style.rtl.css'); ?>

    <!-- jquery files -->
	<?//= CHtml::scriptFile('http://ajax . googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?//= CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?= CHtml::scriptFile('js/vendors/jquery/jquery.js'); ?>

	<!-- template files -->
	<?= CHtml::scriptFile('templates/default/js/main.js'); ?>
</head>
<body>
    <div id="wrapper">
        
        <?php include('header.php'); ?>

        <div class="main-area">
            <div class="panel">                
                <div class="central-panel">	
                    <?= A::app()->view->getContent(); ?>
                </div>    
                <div class="side-panel">					
					<?= FrontendMenu::draw('right', $this->_activeMenu); ?>    
                </div>                    
                <div class="clear"></div>
            </div>
        </div>

        <?php include('footer.php'); ?>
        
    </div>
</body>
</html>