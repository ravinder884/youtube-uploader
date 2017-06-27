<?php
/**
 * Plugin Name: Youtube Upload Video
 * Plugin URI: rvtechnologies.co.in
 * Description: This plugin will allow users to upload video to our youtube channel
 * Version: 1.0.0
 * Author: Manjit Singh
 * Author URI: rvtechnologies.co.in
 * License: GPL2
 */

add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
  add_menu_page('Youtube setting', 'Youtube upload', 'administrator', 'youtube-settings', 'my_plugin_settings_page', 'dashicons-admin-generic');
}

function my_plugin_settings_page() { ?>
    <div class="wrap">
<h1>Youtube Video Upload Setting</h1><small> Enter Client API ID and Client Secret </small>
<form method="post" action="options.php">
    <?php settings_fields( 'my-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Client API ID</th>
        <td><input style="width: 69%;" type="text" name="client_api" value="<?php echo esc_attr( get_option('client_api') ); ?>" /></td>
        </tr>
     
        <tr valign="top">
        <th scope="row">Client Secret</th>
        <td><input style="width: 69%;" type="text" name="client_secret" value="<?php echo esc_attr( get_option('client_secret') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>




<h1>Default Values</h1><small> Default values for video upload </small>
<form method="post" action="options.php">
    <?php settings_fields( 'my-plugin-settings-group_default' ); ?>
    <?php do_settings_sections( 'my-plugin-settings-group_default' ); ?>
    <table class="form-table">


   <tr valign="top">
        <th scope="row">Upload Success Message: </th>
        <td><input style="width: 69%;" type="text" name="yt_video_Success" value="<?php echo esc_attr( get_option('yt_video_Success') ); ?>" /></td>
        </tr>


 <tr valign="top">
        <th scope="row">Terms and service </th>
        <td><input style="width: 69%;" type="text" name="yt_video_terms" value="<?php echo esc_attr( get_option('yt_video_terms') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Video Title: </th>
        <td><input style="width: 69%;" type="text" name="yt_video_title" value="<?php echo esc_attr( get_option('yt_video_title') ); ?>" /></td>
        </tr>
     
        <tr valign="top">
        <th scope="row">Video Description</th>
        <td><input style="width: 69%;" type="text" name="yt_video_description" value="<?php echo esc_attr( get_option('yt_video_description') ); ?>" /></td>
        </tr>


 <tr valign="top">
        <th scope="row">Video Tags</th>
        <td><input style="width: 69%;" type="text" name="yt_video_tags" value="<?php echo esc_attr( get_option('yt_video_tags') ); ?>" /></td>
        </tr>



 <tr valign="top">
<?php  $vcat =  esc_attr( get_option('yt_video_cat') ); ?>
<th scope="row">Default Video Category:</th> 
<td>
<select name="yt_video_cat">
<option value="1" <?php if($vcat == 1) echo "selected"; ?>>  Film & Animation</option>
<option value="2" <?php if($vcat == 2) echo "selected"; ?>>  Cars & Vehicles</option>
<option value="10" <?php if($vcat == 10) echo "selected"; ?>> Music</option>
<option value="15" <?php if($vcat == 15) echo "selected"; ?>> Pets & Animals</option>
<option value="17" <?php if($vcat == 17) echo "selected"; ?>> Sports</option>
<option value="19" <?php if($vcat == 19) echo "selected"; ?>> Travel & Events</option>
<option value="20" <?php if($vcat == 20) echo "selected"; ?>> Gaming</option>
<option value="22" <?php if($vcat == 22) echo "selected"; ?>> People & Blogs</option>
<option value="23" <?php if($vcat == 23) echo "selected"; ?>> Comedy</option>
<option value="24" <?php if($vcat == 24) echo "selected"; ?>> Entertainment</option>
<option value="25" <?php if($vcat == 25) echo "selected"; ?>> News & Politics</option>
<option value="26" <?php if($vcat == 26) echo "selected"; ?>> How-to & Style</option>
<option value="27" <?php if($vcat == 27) echo "selected"; ?>> Education</option>
<option value="28" <?php if($vcat == 28) echo "selected"; ?>> Science & Technology</option>
<option value="29" <?php if($vcat == 29) echo "selected"; ?>> Non-profits & Activism</option>
</select></td></tr>


    </table>
    
    <?php submit_button(); ?>

</form>

</div>
<?php }



add_action( 'admin_init', 'my_plugin_settings' );
function my_plugin_settings() {
  register_setting( 'my-plugin-settings-group', 'client_api' );
  register_setting( 'my-plugin-settings-group', 'client_secret' );

}

// 


add_action( 'admin_init', 'my_plugin_settings_default' );
function my_plugin_settings_default() {
  register_setting( 'my-plugin-settings-group_default', 'yt_video_title' );
  register_setting( 'my-plugin-settings-group_default', 'yt_video_description' );
  register_setting( 'my-plugin-settings-group_default', 'yt_video_tags' );
  register_setting( 'my-plugin-settings-group_default', 'yt_video_cat' );
  register_setting( 'my-plugin-settings-group_default', 'yt_video_Success' );

  register_setting( 'my-plugin-settings-group_default', 'yt_video_terms' );

}

add_shortcode('upload-form', 'upload_video_form');

function upload_video_form() {
 $p_to_root = getcwd();
 ob_start(); 
?>

 <?php

 if(isset($_GET['upload'])) {
 

 if (!isset($_COOKIE['videouploaded'])):
    setcookie('videouploaded', 'yes',  time()+60*60*24*7); // 7 days
 endif; 

 $class = "uploadsuccess";

     echo "<div class='yt-videoupload'>". esc_attr( get_option('yt_video_Success') );
     echo '<embed width="400" height="315" src="https://www.youtube.com/embed/'.$_GET['upload'].'"></embed></div>';

    
 } 

if(isset($_GET['err']) == 'fe') { 
echo '<div class="yt-formerror"><span class="error"><strong>Error</strong>: Invalid Video Format</span></div>';
 } 

  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  pippin_show_error_messages();
?>
<script>
jQuery(document).ready(function() {
     jQuery('#btnpp').click(function () {
            jQuery('#videoupload').click();
        });
});
</script>
<div class="yt-form-upload">
<?php
if(isset($_COOKIE['videouploaded']) && $_COOKIE['videouploaded'] == 'yes' || isset($_GET['upload']))
{ ?>
        <h1 class="<?php echo $class; ?>"> You can upload 1 video per day only </h1>
<?php }
else {
?>
<?php
$vcat =  esc_attr( get_option('yt_video_cat'));
$title = esc_attr( get_option('yt_video_title'));
$desc = esc_attr( get_option('yt_video_description'));
$tags = esc_attr( get_option('yt_video_tags'));
$terms = esc_attr( get_option('yt_video_terms'));


?>
<form method="post" name="upload_form" id="multiple_upload_form" enctype="multipart/form-data" action="<?php echo $actual_link?>">
    <?php echo (!empty($errorMsg))?'<p class="err-msg">'.$errorMsg.'</p>':''; ?>
    <p><label for="title">Title:</label><input type="text" name="title" id="title" value="<?php echo $title; ?>" /></p>
    <p><label for="description">Description:</label> <textarea name="description" id="description"><?php echo $desc; ?></textarea></p>
    <p><label>Video Category:</label> <select name="youtube_cat">

<option value="1" <?php if($vcat == 1) echo "selected"; ?>>  Film & Animation</option>
<option value="2" <?php if($vcat == 2) echo "selected"; ?>>  Cars & Vehicles</option>
<option value="10" <?php if($vcat == 10) echo "selected"; ?>> Music</option>
<option value="15" <?php if($vcat == 15) echo "selected"; ?>> Pets & Animals</option>
<option value="17" <?php if($vcat == 17) echo "selected"; ?>> Sports</option>
<option value="19" <?php if($vcat == 19) echo "selected"; ?>> Travel & Events</option>
<option value="20" <?php if($vcat == 20) echo "selected"; ?>> Gaming</option>
<option value="22" <?php if($vcat == 22) echo "selected"; ?>> People & Blogs</option>
<option value="23" <?php if($vcat == 23) echo "selected"; ?>> Comedy</option>
<option value="24" <?php if($vcat == 24) echo "selected"; ?>> Entertainment</option>
<option value="25" <?php if($vcat == 25) echo "selected"; ?>> News & Politics</option>
<option value="26" <?php if($vcat == 26) echo "selected"; ?>> How-to & Style</option>
<option value="27" <?php if($vcat == 27) echo "selected"; ?>> Education</option>
<option value="28" <?php if($vcat == 28) echo "selected"; ?>> Science & Technology</option>
<option value="29" <?php if($vcat == 29) echo "selected"; ?>> Non-profits & Activism</option>

</select></p>

<p><label>Tags:</label> 
<input type="text" name="tags" placeholder="Tags (e.g., albert einstein, flying pig, mashup)" value="<?php echo $tags; ?>"></p>


    <p><label>Choose Video File:</label>
<input type="file" name="videoFile" id="videoupload"> </p>

   <p>
<input type="checkbox" name="terms" id="terms"> <label> I've read and accept the <a href="<?php echo $terms; ?>">terms and conditions</a></label> </p>

    <p><input name="videoSubmit" id="submit" type="submit" value="Upload"></p>
    </form>
<?php }  ?>
</div>

<?php return ob_get_clean();
}


add_action( 'template_redirect', 'wpse149613_form_process' );
function wpse149613_form_process(){
  ob_start();
ini_set('max_execution_time', 1800);

     if(isset($_REQUEST['videoSubmit'])) {

		$title		= $_POST["title"];
		$description 	= $_POST["description"];
	        $filename 	= $_FILES["videoFile"]['name'];
		$terms 	= $_POST["terms"];


		if($title == '') {
			pippin_errors()->add('title_empty', __('Please enter title'));
		}
		if($description == '') {
			pippin_errors()->add('description_empty', __('Please enter Description'));
		}

		if($filename == '') {
			pippin_errors()->add('file_empty', __('Please select video to upload'));
		}
 if($terms == '') {
			pippin_errors()->add('terms_empty', __('Please accept the terms and conditions'));
		}
		$errors = pippin_errors()->get_error_messages();
 
		if(empty($errors)) {
                        $p_to_root = getcwd();
                        $videoTitle = $_REQUEST['title'];
                        $videoDesc = $_REQUEST['description'];
                        $videoTags = $_REQUEST['tags'];
                        $fileSize = $_FILES['videoFile']['size'];
                        $fileName = basename($_FILES["videoFile"]["name"]);
                        $fileType = $_FILES['videoFile']['type'];

                        $tags =  explode(",", $videoTags);


    $allowedTypeArr = array("video/quicktime", "video/x-matroska", "video/x-flv", "video/mp4", "video/avi", "video/mpeg", "video/mpg", "video/mov", "video/wmv", "video/rm", "video/x-ms-wmv", "video/3gpp", "video/ogg", "video/webm");
 
if(in_array($fileType, $allowedTypeArr)) {
   $key = file_get_contents($p_to_root.'/wp-content/plugins/youtube-upload/token.txt');
if(get_option('client_secret')) {
    $client_secret =  esc_attr( get_option('client_secret') ); 
}
else {
   $client_secret =  '160584722130-gmd1ib93kmck0gjq7nl3i351fvn7frfj.apps.googleusercontent.com'; 
}

if(get_option('client_api')) {
   $client_api =  esc_attr( get_option('client_api') ); 
}
else {
   $client_api =  'kXwvhva-UI1Hlv_1tLPJDfJA'; 
}

require_once 'src/Google/autoload.php'; 
$client_id = $client_api; // Enter your Client ID here
$client_secret = $client_secret; // Enter your Client Secret here

$videoPath =  $_FILES['videoFile']['tmp_name'];
$videoTitle = $_POST['title'];
$videoDescription = $_POST['description'];
$videoCategory = $_POST['youtube_cat'];

$videoTags = $tags;

try{
    // Client init
    $client = new Google_Client();
    $client->setClientId($client_id);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->setAccessToken($key);
    $client->setClientSecret($client_secret);

    if ($client->getAccessToken()) {
        if($client->isAccessTokenExpired()) {
            //echo "token expired";
            $newToken = json_decode($client->getAccessToken());
            $client->refreshToken('1/OgkHLt2nXo3ksdpXqSO_R5vBshAbNGGbk_yv7nvFNv0');
            file_put_contents($p_to_root.'/wp-content/plugins/youtube-upload/token.txt', $client->getAccessToken());
        }
 
        $youtube = new Google_Service_YouTube($client);
         $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($videoTitle);
        $snippet->setDescription($videoDescription);
        $snippet->setCategoryId($videoCategory);
        $snippet->setTags($videoTags);
        $snippet->setDefaultLanguage("en");
        $recordingDetails = new Google_Service_YouTube_VideoRecordingDetails();
        $recordingDetails->setLocationDescription("United States of America");
        $recordingDetails->setRecordingDate("2016-01-20T12:34:00.000Z");
        $locationdetails = new Google_Service_YouTube_GeoPoint();
        $locationdetails->setLatitude("38.8833");
        $locationdetails->setLongitude("77.0167");
        $recordingDetails->setLocation($locationdetails);
        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus("public");
        $status->setPublicStatsViewable(false);
        $status->setEmbeddable(false); // Google defect still not editable https://code.google.com/p/gdata-issues/issues/detail?id=4861
         $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setRecordingDetails($recordingDetails);
        $video->setStatus($status);
         $chunkSizeBytes = 1 * 1024 * 1024;
        $client->setDefer(true);
        $insertRequest = $youtube->videos->insert("status,snippet,recordingDetails", $video);
        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
        fclose($handle);
        if ($status->status['uploadStatus'] == 'uploaded') {
              echo "you video has been uploaded successfully to our youtube channel";
              header('Location:'. get_permalink() .'/?upload='.$status['id']);

        }
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
            exit;
    }  else{   // if file type not supported
         header('Location:'. get_permalink().'?err=fe');
      exit;
       }
    }

  }
}


function pippin_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

function pippin_show_error_messages() {
	if($codes = pippin_errors()->get_error_codes()) {
		echo '<div class="pippin_errors">';
		   foreach($codes as $code){
		        $message = pippin_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}

add_action('init', 'register_script');
function register_script() {
    wp_register_style( 'new_style', plugins_url('ytcss', __FILE__), false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_style');
function enqueue_style(){
   wp_enqueue_style( 'new_style' );
}


