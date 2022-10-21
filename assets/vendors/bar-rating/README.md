jQuery Bar Rating Plugin
-----

### Usage:

#### HTML code
```HTML
<select id="example-css" name="rating" autocomplete="off">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3" selected>3</option>
  <option value="4">4</option>
  <option value="5">5</option>
</select>
```

#### PHP code
```PHP
<!-- Register Bar Rating files -->
<?php A::app()->getClientScript()->registerCssFile('assets/vendors/bar-rating/css/css-stars.css'); ?>
<?php A::app()->getClientScript()->registerScriptFile('assets/vendors/bar-rating/jquery.barrating.min.js', 2); ?>

<?php
A::app()->getClientScript()->registerScript(
    'viewBarRating',
    '$(function() {
        $("#example-css").barrating({
          theme: "css-stars"
        });
    });',
    2
);
?>
```

Examples:
[jQuery Bar Rating Examples](http://antenna.io/demo/jquery-bar-rating/examples/)
---------------
#### JS code
```JS
$('#example').barrating('show', {
  theme: 'my-awesome-theme',
  onSelect: function(value, text, event) {
    if (typeof(event) !== 'undefined') {
      // rating was selected by a user
      console.log(event.target);
    } else {
      // rating was selected programmatically
      // by calling `set` method
    }
  }
});
```