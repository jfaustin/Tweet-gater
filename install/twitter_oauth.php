<?php 
require_once '../inc/Tweetgater/Installer.php';

$installer = new Tweetgater_Installer();

$oauthConfig = Tweetgater_Twitter::getConfigFile();
$installUrl = Tweetgater_Twitter::getBaseUrl();

$config = array(
    'callbackUrl'     => $installUrl . '/install/twitter_callback.php',
    'siteUrl'         => 'https://twitter.com/oauth',
    'consumerKey'     => $oauthConfig->oauth->consumerKey,
    'consumerSecret'  => $oauthConfig->oauth->consumerSecret,
);

$consumer = new Zend_Oauth_Consumer($config);

$session = new Zend_Session_Namespace('twitterAppAuthentication');

$token = $consumer->getRequestToken();

$session->requestToken = serialize($token);

$consumer->redirect();
