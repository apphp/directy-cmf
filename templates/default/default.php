<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
	<!-- don't move it -->
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>    
	<link rel="shortcut icon" href="images/apphp.ico" />    

    <?php echo CHtml::cssFile('templates/default/css/style.css'); ?>
    
    <!-- jquery files -->
	<?php //echo CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?php //echo CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?php echo CHtml::scriptFile('js/vendors/jquery/jquery.js'); ?>

</head>
<body>
    <div id="wrapper">
        
        <?php include('header.php'); ?>        

        <div class="main-area">
            <div class="panel">                
                <div class="central-panel">	
                    <?php echo A::app()->view->getContent(); ?>                    
                </div>    
                <div class="side-panel">					
					<?php echo SiteMenu::draw('right', $this->activeMenu); ?>    
                </div>                    
                <div class="clear"></div>
            </div>
        </div>

        <?php include('footer.php'); ?>
        
    </div>
</body>
</html>