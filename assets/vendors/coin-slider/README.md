Coin Slider (Unique jQuery plug-in - Image Slider)
-----

### Usage:

#### PHP code
```PHP
<?php
	echo CHtml::cssFile('assets/vendors/coin-slider/coin-slider.css');
	if(A::app()->getLanguage('direction') == 'rtl') echo CHtml::cssFile('assets/vendors/coin-slider/coin-slider.rtl.css');
	echo CHtml::scriptFile('assets/vendors/coin-slider/coin-slider.min.js');
?>
```

#### HTML code
```HTML
<div id="coin-slider">
    <a href="javascript:void(0)"><img src="templates/default/images/banner1.jpg" alt="banner 1"><span></span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner2.jpg" alt="banner 2"><span>Description for 2st banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner3.jpg" alt="banner 3"><span>Description for 3st banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner4.jpg" alt="banner 4"><span>Description for 4th banner</span></a>
    <a href="javascript:void(0)"><img src="templates/default/images/banner5.jpg" alt="banner 5"><span></span></a>
</div>
```