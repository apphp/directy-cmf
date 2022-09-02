<div id="header">
	<?php
	    if(CAuth::isLoggedInAsAdmin()){
	        echo CHtml::link(A::t('app', 'Back to Admin Panel'), 'backend/index', array('class'=>'back-to'));
	    }
    ?>
    <a id="logo" href="<?= $this->defaultPage; ?>" title="<?= $this->siteTitle; ?>"><?= $this->siteTitle; ?></a>
	<span id="slogan"><?= $this->siteSlogan; ?></span>

	<div id="top-search">
    <?= SearchForm::draw(); ?>
	</div>

	<div id="language-selector">
    <?= Languages::drawSelector(); ?>
	</div>
</div>    

<div class="tmenu">
    <div class="hmenu-container">
        <?= FrontendMenu::draw('top', $this->_activeMenu); ?>
   </div>
</div>

