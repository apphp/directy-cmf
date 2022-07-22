<div id="header">
	<?php
	    if(CAuth::isLoggedInAsAdmin()){
	        echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
	    }
    ?>
    <a id="logo" href="index/" title="<?php echo $this->siteTitle; ?>"><?php echo $this->siteTitle; ?></a>
	<span id="slogan"><?php echo $this->siteSlogan; ?></span>
    <?php		
        echo Languages::drawSelector();
    ?>
</div>    

<div class="tmenu">
    <div class="hmenu-container">
        <?php echo FrontendMenu::draw('top', $this->activeMenu); ?>
   </div>
</div>

