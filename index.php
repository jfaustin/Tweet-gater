<?php 
require_once 'inc/Tweetgater/Display.php';

$page = 1;

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}

?>
<html>
<head>
    <title>My Organization's Tweets</title>
    <link href="public/css/base.css" rel="stylesheet"/>
</head>
<body>
    <h2 class="tweetHeader">My Organization's Tweets</h2> 
    <?php echo Tweetgater_Display::timeline($page); ?>
</body>
</html>
