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

    <!-- Don't move this tag -->
    <base href="<?= A::app()->getRequest()->getBaseUrl(); ?>" />
    <title><?= CHtml::encode($this->_pageTitle); ?></title>
    <link rel="shortcut icon" href="templates/default/images/apphp.ico" />

    <!-- Web Fonts  -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,700,800,900" rel="stylesheet" type="text/css">

    <!-- CSS Libs -->
    <?php
        if(!TRUE):
            // Link all CSS files at the place
            echo CHtml::cssFiles(
                array(
                    'bootstrap.min.css' => array('media'=>'all'),
                    'theme.css',
                    'font-awesome.min.css',
                    'v-nav-menu.css',
                    'v-portfolio.css',
                    'v-blog.css',
                    'v-bg-stylish.css',
                    'v-shortcodes.css',
                    'theme-responsive.css',
                    'custom.css',
                    (A::app()->getLanguage('direction') == 'rtl' ? 'custom.rtl.css' : '')
                ),
                'templates/default/css/'
            );
        else:
            // Register all CSS files and generate one minified file
            ///A::app()->getClientScript()->registerCss('aaaaaa', 'color:#fff;');
            A::app()->getClientScript()->registerCssFiles(
                array(
					'bootstrap.min.css',
                    'theme.css',
                    'font-awesome.min.css',
                    'v-nav-menu.css',
                    'v-portfolio.css',
                    'v-blog.css',
                    'v-bg-stylish.css',
                    'v-shortcodes.css',
                    'theme-responsive.css',
                    'custom.css',
                    (A::app()->getLanguage('direction') == 'rtl' ? 'custom.rtl.css' : '')
                ),
                'templates/default/css/'
            );
        endif;
    ?>

    <!-- jQuery files -->
    <?//= CHtml::scriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'); ?>
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

<!--JS Libs -->
<?php
    // Register all JS files and generate one minified file at the end of the <body>
    //A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery.js');
    A::app()->getClientScript()->registerScriptFiles(
        array(
            'bootstrap.min.js',
            'jquery.flexslider-min.js',
            'jquery.easing.js',
            'jquery.fitvids.js',
            'jquery.carouFredSel.min.js',
            'theme-plugins.js',
            'jquery.isotope.min.js',
            'theme-core.js'
        ),
        'templates/default/js/',
        2
    );
?>

</body>
</html>