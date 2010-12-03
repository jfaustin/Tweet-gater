<?php 
require_once 'inc/Tweetgater/Display.php';
?>
<html>
<head>
    <title>My Organization's TwitPics</title>
    <link href="public/css/base.css" rel="stylesheet"/>
</head>
<body>
    <?php echo Tweetgater_Display::twitpic(10, 'mini'); ?>
</body>
</html>