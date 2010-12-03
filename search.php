<?php 
require_once 'inc/Tweetgater/Display.php';

$page = 1;

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}

?>
<html>
<head>
    <title>Tweets about My Organization</title>
    <link href="public/css/base.css" rel="stylesheet"/>
</head>
<body>
    <?php echo Tweetgater_Display::search('ncstate', $page); ?>
</body>
</html>