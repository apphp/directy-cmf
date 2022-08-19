<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?php echo CConfig::get('name').' v'.CConfig::get('version'); ?>" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- don't move it -->
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>    
	<link rel="shortcut icon" href="images/apphp.ico" />    

    <?php echo CHtml::cssFile('templates/bootstrap/css/bootstrap.css'); ?>
    <?php echo CHtml::cssFile('templates/bootstrap/css/bootstrap-responsive.css'); ?>
	<?php echo CHtml::cssFile('templates/bootstrap/css/style.css'); ?>

    <!-- jquery files -->
	<?php //echo CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?php //echo CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?php echo CHtml::scriptFile('js/vendors/jquery/jquery.js'); ?>

	<!-- template files -->
	<?php echo CHtml::scriptFile('templates/default/js/main.js'); ?>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>    
</head>
<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
            

            <?php
                if(CAuth::isLoggedInAsAdmin()){
                    echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
                }
            ?>
            <a class="brand" id="logo" href="<?php echo $this->defaultPage; ?>" title="<?php echo $this->siteTitle; ?>"><?php echo $this->siteTitle; ?></a>
            <span id="slogan"><?php //echo $this->siteSlogan; ?></span>


          <div class="nav-collapse collapse">
            
            <?php echo FrontendMenu::draw(
                    'top',
                    $this->_activeMenu,
                    array('menuClass'=>'nav', 'subMenuClass'=>'dropdown-menu', 'dropdownItemClass'=>'dropdown')
                );
            ?>

			<div id="language-selector">
            <?php		
                echo Languages::drawSelector();
            ?>
			</div>
        
			<div id="top-search">
			<?php		
				echo SearchForm::draw();
			?>
			</div>
        
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">


      <!-- Example row of columns -->
      <div class="row">
        <div class="span9">            
            <?php echo A::app()->view->getContent(); ?>            
        </div>
        <div class="span3">          
            <h2>Menu</h2>  
            <?php echo FrontendMenu::draw('right', $this->_activeMenu); ?>              
        </div>
      </div>
      
      <hr>

      <footer>
            <div id="footer">
               <div class="footer">
                    <div class="left-side">
                        <?php echo $this->siteFooter; ?>
                    </div>
                    <div class="central-part">
                        <?php echo FrontendMenu::draw('bottom', $this->activeMenu); ?>
                    </div>            
                    <div class="right-side">
                        <a href="backend/login"><?php echo (CAuth::isGuest() ? A::t('app', 'Admin Login') : ''); ?></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
    </footer>

    </div>
    <!-- /container -->


    <?php //echo CHtml::scriptFile('templates/bootstrap/js/jquery.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-transition.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-alert.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-modal.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-dropdown.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-scrollspy.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-tab.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-tooltip.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-popover.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-button.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-collapse.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-carousel.js'); ?>
    <?php //echo CHtml::scriptFile('templates/bootstrap/js/bootstrap-typeahead.js'); ?>
    
</body>
</html>