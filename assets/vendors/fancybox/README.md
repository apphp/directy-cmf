FancyBox (jQuery Plugin)
-----

### Usage:

#### HTML code
```HTML
<a class="link-category-dishes" rel="category-dishes" href="images/image.png" title=""><img class="dish-icon" src="images/image.png" alt="Icon" /></a>
```

#### PHP code
```PHP
<!-- Register FancyBox files -->
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.mousewheel.pack.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/fancybox/jquery.fancybox.pack'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.js', 2); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/fancybox/jquery.fancybox'.(A::app()->getLanguage('direction') == 'rtl' ? '.rtl' : '').'.css'); ?>

<?php
A::app()->getClientScript()->registerScript(
	'viewMenuCategory',
	'jQuery(document).ready(function(){
		jQuery("a.link-fancybox").fancybox({
			"opacity"		: true,
			"overlayShow"	: false,
			"overlayColor"	: "#000",
			"overlayOpacity": 0.5,
			"titlePosition"	: "inside", /* outside, inside or float */
			"cyclic" : true,
			"transitionIn"	: "elastic", /* fade or none*/
			"transitionOut"	: "fade"
		});
	});	
	',
	2
);
?>
```