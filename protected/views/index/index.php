<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Home Page')));
	$this->_pageTitle = '';
    
    if(!isset($title)) $title = '';
    if(!isset($text)) $text = '';
?>

<h1 class="title"><?= $title; ?></h1>
<div class="block-body">                       
    <?php if(!empty($text)): ?>
		<?= $text; ?>
	<?php else: ?>
		<h1>Welcome to <?= CConfig::get('name'); ?>!</h1>
		
		<hr>
		
		<p>
			This is a default page.
			Apparently, no one has uploaded a new page yet for this site. If you're visiting from a link on
			another site, you may	want to let them know that there's nothing here yet.
		</p>
		
		<p>
			If this is your site, here are <a href="http://www.apphp.com/php-directy-cmf/index.php?page=getting-started">some instructions on how to get
			started</a>.
		</p>
		<p>
			To change the content of this page, open a file: <code>protected/views/index/index.php</code>
			and replace this text with yuor own text or HTML code.
		</p>
		
		<br><br>
		<!-- Post Content -->
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero, obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia corporis eligendi eos magni recusandae laborum minus inventore?</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, tenetur natus doloremque laborum quos iste ipsum rerum obcaecati impedit odit illo dolorum ab tempora nihil dicta earum fugiat. Temporibus, voluptatibus.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, doloribus, dolorem iusto blanditiis unde eius illum consequuntur neque dicta incidunt ullam ea hic porro optio ratione repellat perspiciatis. Enim, iure!</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, nostrum, aliquid, animi, ut quas placeat totam sunt tempora commodi nihil ullam alias modi dicta saepe minima ab quo voluptatem obcaecati?</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum, dolor quis. Sunt, ut, explicabo, aliquam tenetur ratione tempore quidem voluptates cupiditate voluptas illo saepe quaerat numquam recusandae? Qui, necessitatibus, est!</p>
	<?php endif; ?>
</div>

