<?php header('content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="keywords" content="<?= CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?= CHtml::encode($this->_pageDescription); ?>" />
    <meta name="generator" content="<?= CConfig::get('name').' v'.CConfig::get('version'); ?>" />
	<meta name="author" content="<?= CConfig::get('name'); ?>">
    
	<!-- don't move it -->
    <base href="<?= A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?= CHtml::encode($this->_pageTitle); ?></title>    
	<link rel="shortcut icon" href="images/apphp.ico" />    

    <?= CHtml::cssFile('templates/bootstrap/css/bootstrap.min.css'); ?>
	<?= CHtml::cssFile('templates/bootstrap/css/style.css'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

				<?php
					if(CAuth::isLoggedInAsAdmin()){
						echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
					}
				?>
				<a class="navbar-brand" id="logo" href="<?= $this->defaultPage; ?>" title="<?= $this->siteSlogan; ?>"><?= $this->siteTitle; ?></a>
				<!--<span id="slogan"><?//= $this->siteSlogan; ?></span>-->
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<?= FrontendMenu::draw(
						'top',
						$this->_activeMenu,
						array('menuClass'=>'nav navbar-nav', 'subMenuClass'=>'dropdown-menu', 'dropdownItemClass'=>'dropdown')
					);
				?>
				
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Bootstrap Related Resources"  aria-expanded="false">
						<?php
							$lang = A::app()->getLanguage();
							if(!empty($lang)):
								echo '<img src="images/flags/'.A::app()->getLanguage('icon').'" alt="" />&nbsp; '.A::app()->getLanguage('name');
							else:
								echo A::t('app', 'Languages');
							endif;
						?>
						<b class="caret"></b></a>
						<?= Languages::drawSelector(array('display' => 'list', 'class'=>'dropdown-menu')); ?>
					</li>
				</ul>
            </div>
            <!-- /.navbar-collapse -->
 
 			<div id="language-selector">
            <?//= Languages::drawSelector(); ?>
			</div>        
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
		<?= A::app()->view->getLayoutContent(); ?>
    </div>
    <!-- /.container -->

    <!-- jQuery -->
	<?//= CHtml::scriptFile('http://ajax . googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'); ?>
	<?//= CHtml::scriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js'); ?>
    <?//= CHtml::scriptFile('js/vendors/jquery/jquery.js'); ?>
	<?php echo CHtml::scriptFile('templates/bootstrap/js/jquery.js'); ?>
    <!-- Bootstrap Core JavaScript -->
	<?php echo CHtml::scriptFile('templates/bootstrap/js/bootstrap.min.js'); ?>
	<!-- template files -->
	<?= CHtml::scriptFile('templates/bootstrap/js/main.js'); ?>
</body>
</html>