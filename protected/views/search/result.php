<?php 
	Website::setMetaTags(array('title'=>A::t('app', 'Search Results')));
	
	$this->_pageTitle = htmlspecialchars($keywords).' - '.A::t('app', 'Search in').' '.CConfig::get('name');

	// Show categories
	$showCategories = (count($searchCategories) > 1 && empty($currentCategory)) ? true : false;
?>

<h1 class="title"><?= A::t('app', 'Search Results'); ?></h1>
<div class="block-body">	

	<?php if($showCategories){ ?>
		<div class="search-categories-nav">
			<h3><?= A::t('app', 'Page Content'); ?></h3>
			<?php
				if(is_array($searchCategories)){
					echo '<ul class="search-category-links">';
					foreach($searchCategories as $key => $val){
						echo '<li><a href="javascript:void(\'search\');" data-code="'.htmlentities($val['category_code']).'">'.$val['category_name'].'</a></li>';
					}
					echo '</ul>';
				}
			?>
		</div>
	<?php } ?>
	
	<div class="<?= $showCategories ? 'search-results' : 'search-results-wide'; ?>">
		<?php			
			if(is_array($results) && !empty($results)){
				foreach($results as $key => $val){
					
					$itemsCount = 0;
					
					echo '<div class="search-item">';
					echo '<h3 class="search-item-category">'.$val['category_name'].'</h3>';
					echo '<div class="search-item-content">';
					
					if(is_array($val['result']) && !empty($val['result'])){
						foreach($val['result'] as $contentKey => $contentVal){
							if($itemsCount) echo '<div class="horizontal-divider"></div>';

							$title = $contentVal['title'];
							// Highlight keywords in title
							if($highlightResults){
								$title = preg_replace('@('.$keywords.')@si', '<strong style="background-color:yellow">$1</strong>', $contentVal['title']);
							}
							
							// Show title
							echo '<a href="'.CHtml::encode($contentVal['link']).'">'.strip_tags($title).'</a><br>';

							// Show content limited string by 255 chars
							$content = CString::substr(strip_tags($contentVal['content']), 255, '', true);
							
							// Highlight keywords in content
							if($highlightResults){
								$content = preg_replace('@('.$keywords.')@si', '<strong style="background-color:yellow">$1</strong>', $content);
							}
							
							// Draw intro image
							if(!empty($contentVal['intro_image'])){
								echo $contentVal['intro_image'];
							}
							
							echo $content;
							
							$itemsCount++;
						}

						echo '<div class="search-item-footer">';
						echo '<div>';
						if($val['total'] > count($val['result'])){
							echo A::t('app', 'Showing {from}-{to} of {total} results', array('{from}'=>1, '{to}'=>count($val['result']), '{total}'=>$val['total']));
						}else{
							echo A::t('app', 'Total {total} results', array('{total}'=>count($val['result'])));
						}
						echo '</div>';

						echo '<div>';
						if($val['total'] > count($val['result'])){
							echo '<a href="javascript:void(\'search\');" data-code="'.$key.'">'.A::t('app', 'View All').'</a>';
						}
						echo '</div>';
						echo '</div>';
						echo '<div class="clear"></div>';

					}else{
						echo A::t('app', 'No results found for this category!');
					}
						
					echo '</div>';
					echo '</div>';
				}
			}else{
				echo '<div class="search-item">';
				echo '<h3 class="search-item-category">'.(!empty($currentCategory) ? $currentCategory : '&nbsp;').'</h3>';	
				echo '<div class="search-item-content">';
					echo A::t('app', 'No results found!');
				echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>

<?php

// Define events handling for search form
A::app()->getClientScript()->registerScript(
	'searchByCategory',
	'jQuery("ul.search-category-links a, div.search-item-content a").each(function(){
		var frmSearch = jQuery(".form-search"),
			categoryCode = jQuery(this).data("code");
			jQuery(this).click(function(){
				var keywords = frmSearch.find(\'input[name="keywords"]\').val();
				if(keywords != ""){						
					frmSearch.find("input[name=\'search_category\']").val(categoryCode);
					frmSearch.submit();
				}				
			})		
	});',
	3
);

?>