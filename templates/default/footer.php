<div id="footer">
   <div class="footer">
        <div class="left-side">
            <?= $this->siteFooter; ?>
        </div>
        <div class="central-part">
            <?= FrontendMenu::draw('bottom', $this->_activeMenu); ?>
        </div>            
        <div class="right-side">
            <a href="backend/login"><?= (CAuth::isGuest() ? A::t('app', 'Admin Login') : ''); ?></a>
        </div>
        <div class="clear"></div>
    </div>
</div>

<br><br><br><br>