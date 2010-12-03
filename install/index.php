<?php 
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require_once '../inc/Tweetgater/Installer.php';

$installer = new Tweetgater_Installer();
?>
<html>
<head>
<title>Tweets Installer</title>
<style>
body {
	font-family: Arial;
	font-size: 0.9em;
}

h2 {
	margin: 30px 0px 20px 0px;
	border-bottom: 1px dashed #666;
	padding-bottom: 5px;
}

</style>
</head>
<body>
<?php 
$zfCheck = $installer->checkZendFramework();
?>
<h2>Step 1:  Verify Zend Framework version <?php echo Tweetgater_Installer::ZF_REQUIREMENT; ?></h2>
<?php 
if ($zfCheck === true) {
    echo "Successfully found and verified Zend Framework version " . Tweetgater_Installer::ZF_REQUIREMENT;
} else {
    echo $zfCheck;
    echo '<br /><a href="">Try Again</a>';
    die();
}
?>

<?php 
$writable = $installer->checkWritable();

?>
<h2>Step 2:  Make Directories Writable</h2>
The following directories should be made writable:<br /><br />
<?php 
$writableOk = true;
foreach ($writable as $key => $value): 
    if ($value == false) {
        $writableOk = false;
    }
?>
<b><?php echo $key; ?></b> - <?php echo ($value) ? 'Writable' : 'Not Writable'; ?><br />
<?php endforeach; ?>

<?php 
if (!$writableOk) {
    echo '<br /><a href="">Try Again</a>';
    die();
} 
?>
<h2>Step 3:  Create config file</h2>
<?php 


try {
    echo $installer->createConfigFile();
    echo "<br />SUCCESS!";
} catch (Exception $e) {
    echo 'ERROR CREATING CONFIG FILE: ' . $e->getMessage();
    die();
}
?>

<?php 
$options = $installer->verifyOptions();

?>
<h2>Step 4:  Configure Options</h2>
<?php 
if (!$options):
?>
Fill in the following form to setup options within the application.
<br /><br />
<form method="POST" action="options.php">
    <label for="url">URL for the application:</label><br />
    <input type="text" name="url" id="url" value="<?php echo $installer->getProbableUrl(); ?>" /><br /><br />
    <label for="feedTitle">RSS Feed Title:</label><br />
    <input type="text" name="feedTitle" id="feedTitle" /><br /><br />
    <label for="feedAuthor">RSS Feed Author:</label><br />
    <input type="text" name="feedAuthor" id="feedAuthor" /><br /><br />
    <input type="submit" value="Submit" />
</form>
<?php 
    die();
else:
?>
Options have been saved in the config file.
<?php 
endif;

$twitterAppRegistration = $installer->verifyTwitterAppRegistration();
?>

<h2>Step 5:  Setup Application in Twitter</h2>
After you have gone to <a href="http://twitter.com" target="_blank">http://www.twitter.com</a> and setup an account to use, you will need to setup this 
aggregator application with twitter.<br /><br />
<?php if (!$twitterAppRegistration): ?>
    <ul>
        <li>Visit <a href="http://twitter.com/apps" target="_blank">http://twitter.com/apps/</a></li>
        <li>Click the "Register a new application" link.</li>
        <li>Fill in this form with the values that correspond to your setup<br /><br />
            <b>Application Name:</b> The name of your aggregator site.<br />
            <b>Description:</b> Describe your site<br />
            <b>Application Website:</b> The URL to your aggregator<br />
            <b>Organization:</b> The URL to your aggregator<br />
            <b>Website:</b> The URL to your aggregator<br />
            <b>Application Type:</b> Select "Browser"<br />
            <b>Callback URL:</b> Enter the public url of your appliciation to /install/twitter_callback.php.  We think it is "<?php echo Tweetgater_Twitter::getBaseUrl(); ?>/install/twitter_callback.php"<br />
            <b>Default Access type:</b> Select "Read-only"<br />
            <b>Use Twitter for login:</b> Don't check this.<br /><br />
            Enter the Captcha at the bottom then save your application.
        </li>
        <li>Save the form.</li>
    </ul>
    Once you have saved your application, you will be taken to a page that has your <b>Consumer Key</b> and <b>Consumer Secret</b> on them.  Copy and paste
    them into the form below and click submit.<br /><br />
    <form method="POST" action="app_registration.php">
        <label for="comsumerKey">Consumer Key</label><br />
        <input type="text" name="consumerKey" id="consumerKey" /><br /><br />
        <label for="consumerSecret">Consumer Secret</label><br />
        <input type="text" name="consumerSecret" id="consumerSecret" /><br /><br />
        <input type="submit" value="Submit" />
    </form>
<?php 
    die();
else: ?>
    Keys are successfully registered in your configration file.
<?php endif; ?>
<?php 

$twitterToken = $installer->verifyTwitterAccountToken();

?>

<h2>Step 6:  Authenticate with Twitter</h2>

<?php if (!$twitterToken): ?>
To allow this application to speak to twitter on behalf of the user account you setup, you must give it access.
<br /><br />
<a href="twitter_oauth.php">Authenticate with Twitter</a>

<?php 
    die();
else: ?>
Application is configured to speak to twitter.
<?php endif; ?>

<h2>Step 7:  Confirm Twitter Communication</h2>
<?php 
$ncsu = new Tweetgater_Twitter();

try {
    $timeline = $ncsu->getTimeline();
    echo "Success connecting!";
} catch (Exception $e) {
    echo "Error Connecting: " . $e->getMessage();
    die();
}
?>

<h2>Step 8:  Cleanup</h2>
<ul>
    <li>You should remove the /install directory</li>
    <li>You should remove write permissions from the /config directory and file</li>
</ul>
<br />
<h1>You should be all set now!</h1>
You can configure these changes by modifying the config.ini file in the /config folder.<br /><br />
<h1><a href="<?php echo Tweetgater_Twitter::getBaseUrl(); ?>">Visit Your Site</a></h1>
<br /><br /><br />

</body>
</html>