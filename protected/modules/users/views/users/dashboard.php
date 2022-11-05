<?php
    Website::setMetaTags(array('title'=>A::t('users', 'Dashboard')));

    $this->_activeMenu = 'users/dashboard';   
?>

<h1 class="title"><?= A::t('users', 'Dashboard'); ?></h1>
<div class="block-body">

<h2><?= A::t('users', 'Hi').', '.CAuth::getLoggedName(); ?></h2>
<p><?= A::t('users', 'Welcome to the User Dashboard!'); ?></p>

<ul class="dashboard-links">
    <li><a href="users/dashboard"><?= A::t('users', 'Dashboard'); ?></a><br></li>
    <li><a href="users/myAccount"><?= A::t('users', 'My Account'); ?></a></li>
    <li><a href="users/logout"><?= A::t('users', 'Logout'); ?></a></li>    
</ul>
</div>

<div class="cb"></div>

