<?php 
require_once '../inc/Tweetgater/Installer.php';

$installer = new Tweetgater_Installer();

$oauthConfig = Tweetgater_Twitter::getConfigFile();
$installUrl = Tweetgater_Twitter::getBaseUrl();

$session = new Zend_Session_Namespace('twitterAppAuthentication');

$config = array(
    'callbackUrl'     => $installUrl . '/install/twitter_callback.php',
    'siteUrl'         => 'https://twitter.com/oauth',
    'consumerKey'     => $oauthConfig->oauth->consumerKey,
    'consumerSecret'  => $oauthConfig->oauth->consumerSecret,
);

$consumer = new Zend_Oauth_Consumer($config);

$accessToken = $consumer->getAccessToken($_GET, unserialize($session->requestToken));

$installer->writeOauthToken($accessToken->getParam('oauth_token'), $accessToken->getParam('oauth_token_secret'));

$session->unsetAll();

header('Location: index.php');