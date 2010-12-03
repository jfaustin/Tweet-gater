<?php
set_include_path(realpath(dirname(__FILE__) . '/../'));

class Tweetgater_Installer
{
    const ZF_REQUIREMENT = '1.10.6';
    
    protected $_writable = array(
        '../config',
        '../cache',
    );
    
    public function __construct()
    {
        if (is_file('../inc/Zend/Loader/Autoloader.php')) {
            require_once '../inc/Zend/Loader/Autoloader.php';
            $loader = Zend_Loader_Autoloader::getInstance();
            $loader->setFallbackAutoloader(true);
        }
    }
    
    public function checkZendFramework()
    {
        if (!is_dir('../inc/Zend')) {
            return 'Zend Framework library not found in /inc/Zend';
        }
        
        require_once '../inc/Zend/Version.php';
        
        $versionCompare = Zend_Version::compareVersion(self::ZF_REQUIREMENT);

        if ($versionCompare == 1) {
            return 'Zend Framework library you are using is older than the recommended version of ' . self::ZF_REQUIREMENT;
        } elseif ($versionCompare == -1) {
            return 'Zend Framework library you are using is newer than the recommended version of ' . self::ZF_REQUIREMENT;
        }  
        
        return true;
    }
    
    public function checkWritable()
    {
        $result = array();
        
        foreach ($this->_writable as $w) {
            $result[$w] = is_writable($w);
        }
        
        return $result;
    }    
    
    public function createConfigFile()
    {
        $filepath = Tweetgater_Twitter::getConfigFilePath();
        $source = str_replace('config.php', 'config.default.php', $filepath);
        
        $ret = '';
        if (is_file($filepath)) {
            $ret = 'File exists, checking writability....<br />';
        } else {
            
            if (!copy($source, $filepath)) {
                throw new Exception('Can not copy config.default.php to config.php.  You should do this manually.');
            }
            
            chmod($filepath, 0766);
            
            $ret = 'Creating file config.php<br />';
        }
        
        if (is_writable($filepath)) {
            $ret .= 'File is writable<br />';
        } else {
            if (!chmod($filepath, 0766)) {
                throw new Exception('Can not make config file writable');
            }
            
            $ret .= 'Modifying file for writing<br />';
        }
        
        return $ret;
    }
    
    public function verifyOptions()
    {
        $config = Tweetgater_Twitter::getConfigFile();

        if ($config->site->url == '' || $config->feed->title == '' || $config->feed->author == '') {
            return false;
        }
        
        return true;
    }
    
    public function writeOptions($url, $feedTitle, $feedAuthor)
    {
        $config = Tweetgater_Twitter::getConfigFile(true);
        
        $config->site->url = $url;
        $config->feed->title = $feedTitle;
        $config->feed->author = $feedAuthor;
        
        $writer = new Zend_Config_Writer_Array(array('config' => $config, 'filename' => Tweetgater_Twitter::getConfigFilePath()));
        
        $writer->write(null);
        
        // Kinda hackish, but this ensures that concurrency problems from the file write don't show up
        sleep(5);
    }
    
    public function getProbableUrl()
    {
        $baseUrl = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/install'));
        
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $url = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $baseUrl;        
        
        return $url;
    }
        
    public function verifyTwitterAppRegistration()
    {
        $config = Tweetgater_Twitter::getConfigFile();
        
        if ($config->oauth->consumerKey == '' || $config->oauth->consumerSecret == '') {
            return false;
        }
        
        return true;
        
    }
    
    public function verifyTwitterAccountToken()
    {
         $config = Tweetgater_Twitter::getConfigFile();
        
        if ($config->oauth->token == '') {
            return false;
        }
        
        return true;
    }
    
    public function writeOauthAppInfo($consumerKey, $consumerSecret)
    {
        $config = Tweetgater_Twitter::getConfigFile(true);
        
        $config->oauth->consumerKey = $consumerKey;
        $config->oauth->consumerSecret = $consumerSecret;
        
        $writer = new Zend_Config_Writer_Array(array('config' => $config, 'filename' => Tweetgater_Twitter::getConfigFilePath()));
        
        $writer->write();
        
        // Kinda hackish, but this ensures that concurrency problems from the file write don't show up
        sleep(5);
    }
    
    public function writeOauthToken($token, $tokenSecret)
    {
        $config = Tweetgater_Twitter::getConfigFile(true);
        
        $config->oauth->token = $token;
        $config->oauth->tokenSecret = $tokenSecret;
        
        $writer = new Zend_Config_Writer_Array(array('config' => $config, 'filename' => Tweetgater_Twitter::getConfigFilePath()));
        
        $writer->write();
        
        // Kinda hackish, but this ensures that concurrency problems from the file write don't show up
        sleep(5);
    }    
    
}