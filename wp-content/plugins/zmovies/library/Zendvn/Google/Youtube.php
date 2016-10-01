<?php
namespace Zendvn\Google;

require_once ZMOVIES_VENDOR_PATH . DS . 'autoload.php';
require_once ZMOVIES_VENDOR_PATH . DS . 'src/Google/Client.php';

class Youtube{
    
    private $_client;
    private $_youtube;
    
    public function __construct(){
        session_start();
    }
    
    
    public function setClient(){
        $OAUTH2_CLIENT_ID       = '30262353331-6hlm88l6iu17broappsto01p75o4kt0l.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET   = 'w3uBQ86aOXNyqVerbmNGWEBX';
        
        $this->_client = new \Google_Client();
        $this->_client->setClientId($OAUTH2_CLIENT_ID);
        $this->_client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $this->_client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
        $this->_client->setRedirectUri('http://localhost/animemovies/wp-admin/edit.php?post_type=zmovies&ztask=update');
        
        // Define an object that will be used to make all API requests.
        if (isset($_GET['code'])) {
            if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }
            $this->_client->authenticate($_GET['code']);
            $_SESSION['token'] = $this->_client->getAccessToken();
            header('Location: ' . $redirect);
        }
        
        if (isset($_SESSION['token'])) {
            $this->_client->setAccessToken($_SESSION['token']);
            if ($this->_client->isAccessTokenExpired()) {
                 
                $currentTokenData = json_decode($_SESSION['token']);
        
                if (isset($currentTokenData->refresh_token)) {
                    $this->_client->refreshToken($currentTokenData->refresh_token);
                }
            }
        }
    }
    
    public function getAccessToken(){
        if ($this->_client->getAccessToken()) {
            $this->_youtube = new \Google_Service_YouTube($this->_client);
            
        }
    }
}