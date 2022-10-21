jQuery Timepicker Addon
-----
[Website](http://trentrichardson.com/examples/timepicker/)<br>
[Author: Trent Richardson](http://trentrichardson.com)<br>
[Online Demo](http://trentrichardson.com/examples/timepicker/)


### Usage:

How to add new language:
-----
1.Define new language settings, where 'xx' is a new language abbreviation:

#### JS code
```JS
$.timepicker.regional['xx'] = {
    timeOnlyTitle: 'Choose Time',
    timeText: 'Time',
    hourText: 'Hour',
    minuteText: 'Minute',
    secondText: 'Second',
    millisecText: 'Millisecond',
    microsecText: 'Microsecond',
    timezoneText: 'Time Zone',
    currentText: 'Ara',
    closeText: 'Done',
    timeFormat: 'HH:mm',
    timeSuffix: '',
    amNames: ['AM', 'A'],
    pmNames: ['PM', 'P'],
    isRTL: false
};
```

2.Add it to
in assets/vendors/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.js<br>
and<br>
in assets/vendors/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.min.js



#### PHP code
```PHP
<!-- Register datetimepicker files -->
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css'); ?>
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/datetimepicker/jquery-ui-timepicker-addon.min.css'); ?>

<?php // A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery-ui.min.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/datetimepicker/jquery-ui-timepicker-addon.min.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.min.js', 2); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/datetimepicker/jquery-ui-slideraccess.min.js', 2); ?>
```

#### HTML code
```HTML
<!-- HTML example -->
<input type="text" name="input_example_1" id="input_example_1" value="08/20/2014" />
<input type="text" name="input_example_2" id="input_example_2" value="08/20/2014 01:22 pm" />
<input type="text" name="input_example_3" id="input_example_3" value="" />

<?php
A::app()->getClientScript()->registerScript(
	'timePickerExample',
	'jQuery(document).ready(function(){
		//$.timepicker.setDefaults($.timepicker.regional["es"]);
		$("#input_example_1").datetimepicker({
			showTimepicker: false,
			showTime: false,
			//isRTL: true
		});			  
		$("#input_example_2").datetimepicker({
			timeInput: true,
			timeFormat: "hh:mm tt"
		});			  
		$("#input_example_3").timepicker({
			hourMin: 8,
			hourMax: 16
		});			  
	});',
	1
);
?>
```