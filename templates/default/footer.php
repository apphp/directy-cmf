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


<br><br><br><br>