<div id="header">
	<?php
	    if(CAuth::isLoggedInAsAdmin()){
	        echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
	    }
    ?>
    <a id="logo" href="<?php echo $this->defaultPage; ?>" title="<?php echo $this->siteTitle; ?>"><?php echo $this->siteTitle; ?></a>
	<span id="slogan"><?php echo $this->siteSlogan; ?></span>

	<div id="top-search">
    <?php		
        echo SearchForm::draw();
    ?>
	</div>

	<div id="language-selector">
    <?php
        echo Languages::drawSelector();
    ?>
	</div>
</div>    

<div class="tmenu">
    <div class="hmenu-container">
        <?php echo FrontendMenu::draw('top', $this->_activeMenu); ?>
   </div>
</div>

