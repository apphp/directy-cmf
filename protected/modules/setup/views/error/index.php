<?php
    $this->_pageTitle = A::t('app', 'Error 404');
?>

<h2><?php echo $header; ?></h2>

<p>
<?php echo CWidget::create('CMessage', array('error', $text)).'<br>'; ?>
</p>
