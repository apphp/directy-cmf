<?php
	Website::setMetaTags(array('title'=>A::t('app', 'Site Offline')));
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $siteTitle; ?></title>
	<style type="text/css">        
		#wrapper { background-color:#f1f2f3; margin:100px auto; text-align:center; width:500px; border:1px solid #ccc; border-radius:5px; padding-top:0px; -moz-box-shadow: 4px 5px 5px 1px #777; -webkit-box-shadow: 4px 5px 5px 1px #777; box-shadow: 4px 5px 5px 1px #777; background: -webkit-gradient(linear, left top, left bottom, from(#f1f2f3), to(#ffffff)); background: -moz-linear-gradient(top,  #f1f2f3,  #ffffff); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f1f2f3", endColorstr="#ffffff"); }
		#header { height:100px; margin-top:0px; }
		#content {height:200px; padding:30px 0;color:#3f4c6b; }
		#footer { margin:10px; color:#36393d; }
		h1 { color:#3f4c6b; }
		h3 { color:#555; }
        a { color: #336699; }
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1><?php echo $siteTitle; ?></h1>
			<h3><?php echo $slogan; ?></h3>
		</div>    
		<div id="content"><?php echo $offlineMessage; ?></div>
		<div id="footer"><?php echo $footer; ?></div>
	</div>
</body>
</html>
