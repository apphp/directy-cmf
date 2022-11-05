<?php 
    Website::setMetaTags(array('title'=>A::t('testimonials', 'All Testimonials')));
	
	$this->_pageTitle = A::t('testimonials', 'All Testimonials');

	// Define active menu	
	$this->_activeMenu = 'testimonials/viewAll';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('testimonials', 'All Testimonials');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('testimonials', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('testimonials', 'All Testimonials')),
	);
?>

<h1 class="title"><?= A::t('testimonials', 'All Testimonials'); ?></h1>
<div class="block-body">                       
    
<?php
if($actionMessage != ''):
    echo $actionMessage;
else:
    $showTestimonials = count($testimonials);
    for($i=0; $i < $showTestimonials; $i++):
        $country = ($testimonials[$i]['author_country'] != '') ? ', '.$testimonials[$i]['author_country'] : '';
        $city = ($testimonials[$i]['author_city'] != '') ? ', '.$testimonials[$i]['author_city'] : '';
        $company = ($testimonials[$i]['author_company'] != '') ? ' &laquo;'.$testimonials[$i]['author_company'].'&raquo;' : '';
        $author_position = ($testimonials[$i]['author_position'] != '') ? ', '.$testimonials[$i]['author_position'] : '';
		$author_image = ($testimonials[$i]['author_image'] != '') ? ', '.$testimonials[$i]['author_image'] : '';
?>
	<div class="row pull-bottom-small">
		<div class="col-md-12 pi-testimonial pi-section-grey">
			<div class="pi-testimonial-content pi-testimonial-content-quotes">
				<p class="testimonials-text"><q><?= $testimonials[$i]['testimonial_text']; ?></q></p>    
			</div>
			<?php if(!empty($author_image)): ?>
				<figure class="tl_author_img">
					<img style="width:150px;height:150px;" src="assets/modules/testimonials/images/authors/<?= ($testimonials[$i]['author_image']) ? $testimonials[$i]['author_image'] : 'no_image.png'; ?>" class="attachment-thumbnail post-image" />
				</figure>
			<?php endif; ?>
			<p class="testimonials-author-info"><?= $testimonials[$i]['author_name'].$company.$author_position.$city.$country; ?></p>
		</div>
	</div>
<?php
    endfor;

    if($totalTestimonials > 1):
        echo CWidget::create('CPagination', array(
            'actionPath'   => 'testimonials/viewAll',
            'currentPage'  => $currentPage,
            'pageSize'     => $pageSize,
            'totalRecords' => $totalTestimonials,
            'showResultsOfTotal' => false,
            'linkType' => 0,
            'paginationType' => 'justNumbers'
        ));            
    endif;
endif;
?>    
</div>
