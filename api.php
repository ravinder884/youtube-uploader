<?php

$key = file_get_contents('token.txt');
require_once 'src/Google/autoload.php'; 
$client_id = '285710402617-4p9qdch5bl3oitqukj8lfhkjca17gdub.apps.googleusercontent.com'; // Enter your Client ID here
$client_secret = 'deKKeKYR2SUTW8_V_pgpWRax'; // Enter your Client Secret here

$videoPath = "videos/nayandtatimyina-SampleVideo_720x480_1mb.mp4";
$videoTitle = "new upload";
$videoDescription = "This is the YouTube video's description";
$videoCategory = "22";
$videoTags = array("first tag","second tag","third tag");

try{
    // Client init
    $client = new Google_Client();
    $client->setClientId($client_id);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->setAccessToken($key);
    $client->setClientSecret($client_secret);


//  $client->refreshToken('1\\\-xncrHTz-SmZct26nqzHZ8kRO5fNmH9dBaK7SYqEpKw');
           // file_put_contents('token.txt', $client->getAccessToken());

    if ($client->getAccessToken()) {


    	//echo "fsfsdfsdf"; die;

        /**
         * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
         */
        if($client->isAccessTokenExpired()) {
            echo "token expired";
            $newToken = json_decode($client->getAccessToken());
           $client->refreshToken('1/-xncrHTz-SmZct26nqzHZ8kRO5fNmH9dBaK7SYqEpKw');
            file_put_contents('token.txt', $client->getAccessToken());
        }
 
        $youtube = new Google_Service_YouTube($client);
 
        // Create a snipet with title, description, tags and category id
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($videoTitle);
        $snippet->setDescription($videoDescription);
        $snippet->setCategoryId($videoCategory);
        $snippet->setTags($videoTags);
        $snippet->setDefaultLanguage("en");
      //  $snippet->setDefaultAudioLanguage("en");

        $recordingDetails = new Google_Service_YouTube_VideoRecordingDetails();
        $recordingDetails->setLocationDescription("United States of America");
        $recordingDetails->setRecordingDate("2016-01-20T12:34:00.000Z");
        $locationdetails = new Google_Service_YouTube_GeoPoint();
        $locationdetails->setLatitude("38.8833");
        $locationdetails->setLongitude("77.0167");
        $recordingDetails->setLocation($locationdetails);

        // Create a video status with privacy status. Options are "public", "private" and "unlisted".
        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus("public");
        $status->setPublicStatsViewable(false);
        $status->setEmbeddable(false); // Google defect still not editable https://code.google.com/p/gdata-issues/issues/detail?id=4861
 
        // Create a YouTube video with snippet and status
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setRecordingDetails($recordingDetails);
        $video->setStatus($status);
 
        // Size of each chunk of data in bytes. Setting it higher leads faster upload (less chunks,
        // for reliable connections). Setting it lower leads better recovery (fine-grained chunks)
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $client->setDefer(true);

        // Create a request for the API's videos.insert method to create and upload the video.
        $insertRequest = $youtube->videos->insert("status,snippet,recordingDetails", $video);

        // Create a MediaFileUpload object for resumable uploads.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));

        // Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        /**
         * Video has successfully been uploaded, now lets perform some cleanup functions for this video
         */
        if ($status->status['uploadStatus'] == 'uploaded') {
            // Actions to perform for a successful upload
              echo "you video has been uploaded successfully to our youtube channel";

        }

        // If you want to make other calls after the file upload, set setDefer back to false
        $client->setDefer(true);

    } else{
        // @TODO Log error
        echo 'Problems creating the client';
    }

} catch(Google_Service_Exception $e) {
    print "Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage();
    print "Stack trace is ".$e->getTraceAsString();
}catch (Exception $e) {
    print "Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage();
    print "Stack trace is ".$e->getTraceAsString();
}

?>