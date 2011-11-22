<?php 
require_once '../inc/Tweetgater/Installer.php';

$installer = new Tweetgater_Installer();

if (!isset($_POST['url']) || !isset($_POST['feedTitle']) || !isset($_POST['feedAuthor'])) {
    die('Consume Key and Consumer Secret must be set.');
}

$url = trim(htmlentities($_POST['url']));
$feedTitle = trim(htmlentities($_POST['feedTitle']));
$feedAuthor = trim(htmlentities($_POST['feedAuthor']));

if ($url == '' || $feedTitle == '' || $feedAuthor == '') {
    die('URL, Feed Title and Feed Author must be set.');
}

$installer->writeOptions($url, $feedTitle, $feedAuthor);

header('Location: index.php');