<!DOCTYPE html>
<html>
    <head>
        <base href="<?php echo $baseUrl; ?>">
        <link rel="stylesheet" type="text/css" href="assets/vendors/toastr/toastr.min.css" />
        <link rel="stylesheet" type="text/css" href="assets/modules/polls/css/polls.css" />
        <link rel="stylesheet" type="text/css" href="css/font-awesome.css" />
        <script type="text/javascript" src="assets/vendors/jquery/jquery.js"></script>

        <link rel="stylesheet" type="text/css" href="templates/default/css/style.css" />
        <link rel="stylesheet" type="text/css" href="templates/default/css/custom.css" />
        <style>
            html{height:100%;}
            body {padding:0;margin:0;border:0;background:<?php echo !empty($backgroundColor) ? $backgroundColor : '#fff'; ?>;color:<?php echo !empty($colorText) ? $colorText : '#000'; ?>;position:absolute;top:10px;bottom:10px;left:10px;right:10px;}
            .polls-archive {display:none;}
        </style>
    </head>
    <body>
        <?php echo $widget; ?>
        <script type="text/javascript" src="assets/vendors/jquery/jquery.js"></script>
        <script type="text/javascript">
            <?php echo PollsComponent::getJSPollsBlock(); ?>
        </script>
    </body>
</html>
