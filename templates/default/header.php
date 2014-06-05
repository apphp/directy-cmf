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
        <?php echo SiteMenu::draw('top', $this->activeMenu); ?>
   </div>
</div>

<!-- banners -->
<?php if($this->isHomePage){ ?>
<div id="coin-slider">
    <a href="javascript:void(0)"><img src="templates/default/images/banner1.jpg" alt=""><span></span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner2.jpg" alt=""><span>Description for 2st banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner3.jpg" alt=""><span>Description for 3st banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner4.jpg" alt=""><span>Description for 4th banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner5.jpg" alt=""><span></span></a>
</div>
<?php } ?>
