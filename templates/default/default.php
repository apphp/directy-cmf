<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?= CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?= CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?= CConfig::get('name').' v'.CConfig::get('version'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- don't move it -->
    <base href="<?= A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?= CHtml::encode($this->_pageTitle); ?></title>
	<link rel="shortcut icon" href="templates/default/images/apphp.ico" />    

    <!-- Web Fonts  -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,700,800,900" rel="stylesheet" type="text/css">

    <!-- Libs CSS -->
	<?= CHtml::cssFile('templates/default/css/bootstrap.min.css'); ?>
    <?= CHtml::cssFile('templates/default/css/bootstrap.min.css'); ?>
    <?= CHtml::cssFile('templates/default/css/theme.css'); ?>
    <?= CHtml::cssFile('templates/default/css/font-awesome.min.css'); ?>
    <?= CHtml::cssFile('templates/default/css/v-nav-menu.css'); ?>
    <?= CHtml::cssFile('templates/default/css/v-portfolio.css'); ?>
    <?= CHtml::cssFile('templates/default/css/v-blog.css'); ?>
    <?= CHtml::cssFile('templates/default/css/v-bg-stylish.css'); ?>
    <?= CHtml::cssFile('templates/default/css/v-shortcodes.css'); ?>
    <?= CHtml::cssFile('templates/default/css/theme-responsive.css'); ?>

    <!-- Custom CSS -->
	<?= CHtml::cssFile('templates/default/css/custom.css'); ?>
	<?php if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/default/css/custom.rtl.css'); ?>

    <?//= CHtml::cssFile('templates/default/css/style.css'); ?>
    <?php //if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('templates/default/css/style.rtl.css'); ?>

    <!-- jquery files -->
	<?//= CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?//= CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?= CHtml::scriptFile('assets/vendors/jquery/jquery.js'); ?>
</head>
<body>

	<?php include('header.php'); ?>

    <div id="container">

		<?php
			# Show banner only on home page
			if(Website::isDefaultPage()):
				include('slider.php');
			endif;
		?>
	
		<?php
			# Show breadcrumbs
			$breadCrumbs = A::app()->view->_breadCrumbs;
			if(!Website::isDefaultPage() && !empty($breadCrumbs)):
		?>
			<div class="v-page-heading v-bg-stylish v-bg-stylish-v1">
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<div class="heading-text">
								<h4 class="entry-title"><?= A::app()->view->_breadcrumbsTitle != '' ? A::app()->view->_breadcrumbsTitle : A::app()->view->_pageTitle; ?></h4>
							</div>
							<?php
								CWidget::create('CBreadCrumbs', array(
									'links' => A::app()->view->_breadCrumbs,
									'separator' => '&nbsp;/&nbsp;',
									'wrapperClass' => 'breadcrumb',
									'return' => false
								));
							?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php
			$columnSide = (A::app()->getLanguage('direction') == 'rtl') ? 'left' : 'right';
		?>
		<div class="v-page-wrap has-<?= $columnSide; ?>-sidebar has-one-sidebar">	
			<div class="container">
                <div class="row">
                    <div class="col-sm-9">
						<?= A::app()->view->getContent(); ?>
					</div>
					<aside class="sidebar <?= $columnSide; ?>-sidebar col-sm-3">
						<?= FrontendMenu::draw('right', $this->_activeMenu); ?>    
					</aside>
				</div>
			</div>
		</div>

		<?php include('footer.php'); ?>
    </div>

    <!--// BACK TO TOP //-->
    <div id="back-to-top" class="animate-top"><i class="fa fa-angle-up"></i></div>
        
    <!-- Libs -->
    <?= CHtml::scriptFile('templates/default/js/bootstrap.min.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/jquery.flexslider-min.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/jquery.easing.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/jquery.fitvids.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/jquery.carouFredSel.min.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/theme-plugins.js'); ?>
    <?= CHtml::scriptFile('templates/default/js/jquery.isotope.min.js'); ?>

    <?= CHtml::scriptFile('templates/default/js/theme-core.js'); ?>

</body>
</html>