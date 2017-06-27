<?php
    // OAUTH Configuration
    $oauthClientID = '285710402617-4p9qdch5bl3oitqukj8lfhkjca17gdub.apps.googleusercontent.com';
    $oauthClientSecret = 'deKKeKYR2SUTW8_V_pgpWRax';
    $baseUri = 'http://localhost/upload-video-to-youtube-using-php/';
    $redirectUri = 'http://localhost/upload-video-to-youtube-using-php/youtube_upload.php';



    
    define('OAUTH_CLIENT_ID',$oauthClientID);
    define('OAUTH_CLIENT_SECRET',$oauthClientSecret);
    define('REDIRECT_URI',$redirectUri);
    define('BASE_URI',$baseUri);
    
    // Include google client libraries
    require_once 'src/Google/autoload.php'; 
    require_once 'src/Google/Client.php';
    require_once 'src/Google/Service/YouTube.php';
    session_start();
    
    $client = new Google_Client();
    $client->setClientId(OAUTH_CLIENT_ID);
    $client->setClientSecret(OAUTH_CLIENT_SECRET);
    $client->setScopes('https://www.googleapis.com/auth/youtube');
    $client->setRedirectUri(REDIRECT_URI);
    
    // Define an object that will be used to make all API requests.
    $youtube = new Google_Service_YouTube($client);
    
?>