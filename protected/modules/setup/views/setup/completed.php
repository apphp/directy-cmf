<?php
	A::app()->view->setMetaTags('title', A::t('setup', 'Completed'));

    $this->_activeMenu = $this->_controller.'/'.$this->_action;
    $baseUrl = A::app()->getRequest()->getBaseUrl();
?>

<h1><?php echo A::t('setup', 'Completed'); ?></h1>

<?php echo $actionMessage; ?>
    
<p>
    <?php echo A::t('setup', 'Your website is available at'); ?> <a href="<?php echo $baseUrl; ?>"><?php echo $baseUrl; ?></a>
    <br><br>
    <?php echo A::t('setup', 'You may login to Admin Panel'); ?>:<br>
    <?php echo A::t('setup', 'Username is'); ?>: <i><?php echo $username; ?></i>
    <br>
    <?php echo A::t('setup', 'Password is'); ?>: <i><?php echo $password; ?></i>
    <br><br>
</p>


