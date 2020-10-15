<?php

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;
use LINE\LINEBot\Message\MultipleMessages;
use LINE\LINEBot\Message\RichMessage\Markup;

require_once 'vendor/autoload.php';

$setting = require('settings.php');
$channelId = $setting['channelId'];
$channelSecret = $setting['channelSecret'];
$accessToken = $setting['accessToken'];

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
		// Get text sent
			$text = $event['message']['text'];			
			// Get replyToken
			$replyToken = $event['replyToken'];
			if ($text == 'HELP') {
				$msg  = "CHECK คือ การตรวจสอบสถานะการใช้งานรับข้อมูล Line ของท่าน" ."\n" ."REGISTER คือ การยืนยันลงทะเบียนเพื่อใช้งานรการับข้อมูลผ่าน Line โดยการพิม REGISTER:<verify code>";
			} else if ($text == 'CHECK') {
				$msg = check_info($event['source']['userId']);	
				exit();
			//} else if (preg_match('/^REGISTER\:[A-Z0-9\-]{4,8}\:.+/', $text)) {
				} else if (preg_match('/^REGISTER\:.+/', $text)) {
				$info = 	explode(":",$text);
				$verify_id = $info[1];
				$objConnect = mysqli_connect("10.10.19.138","webbom","bombom") or die(mysql_error());
				mysqli_set_charset($objConnect, "utf8");
				mysqli_select_db($objConnect,"svgarena");		
				$strSQL = "SELECT member_id FROM otp_expiry WHERE votp = '$verify_id'";
				$objQuery = mysqli_query($objConnect,$strSQL) or die (mysql_error());
				$no = mysqli_num_rows($objQuery);
				if ($no > 0) {
					while($obResult = mysqli_fetch_array($objQuery,MYSQLI_ASSOC)) {
						if (isset($obResult['member_id'])) {							
							$member_id = $obResult['member_id'];
							$strSQL2 = "SELECT member_id,firstname,lastname FROM users WHERE member_id = '$member_id'";
							$objQuery2 = mysqli_query($objConnect,$strSQL2) or die (mysql_error());
							$no2 = mysqli_num_rows($objQuery2);
							while($obResult2 = mysqli_fetch_array($objQuery2,MYSQLI_ASSOC)) {
							    $res_info = $obResult["member_id"]." ".$obResult2["firstname"]." ".$obResult2["lastname"];
							}	
						}
					} 
					sleep(1);
					register_form($event['source']['userId'],$res_info,$member_id,$line_id);
				    mysqli_close($objConnect); 	
				} else {			
					$msg = 'ไม่พบข้อมูลรหัสสมาชิกของท่าน';
					sleep(1);
					reply_msg($replyToken,$msg);	
				}
				exit();			
		    	} else {
				$msg = "ท่านกรอกข้อมูลไม่ถูกต้อง โปรดตรวจสอบความถูกต้อง หรือ พิม 'HELP'เพื่อดูวิธีการใช้งาน".$verify_id;
			}
			sleep(1);
			reply_msg($replyToken,$msg);	
		} else if ($event['type'] == 'postback') {
			$text = $event['postback']['data'];
			$replyToken = $event['replyToken'];
			if (preg_match('/^#123confirm\:+/', $text)) {
				$mid = $event['source']['userId'];
				$info = 	explode(":",$text);
				$member_id = $info[1];					
				$objConnect = mysqli_connect("10.10.19.138","webbom","bombom") or die(mysql_error());
				mysqli_set_charset($objConnect, "utf8");
				mysqli_select_db($objConnect,"svgarena");		
				$strSQL = "UPDATE users SET mid = '$mid' WHERE member_id = '$member_id'";
				$objQuery = mysqli_query($objConnect,$strSQL) or die (mysql_error());
				$no = mysqli_num_rows($objQuery);					
				if ($no > 0) {
					$msg = 'Line ID ของท่านลงทะเบียนกับรหัสพนักงาน'.' '.$staff_id.' '.'สามารถใช้งานได้แล้ว';					
				} else {//if (isset($chk_res_info){
					$msg = 'รหัสสมาชิกของท่านมีการลงทะเบียนใช้งานอยู่ในระบบแล้ว';
				} 
				mysqli_close($objConnect);
				sleep(1);	
				reply_msg($replyToken,$msg);
			} else {
				$msg = 'การลงทะเบียนของท่านถูกยกเลิกแล้ว ขอบคุณที่ใช้บริการ';
				sleep(1);
				reply_msg($replyToken,$msg);
			}						
		}								
	}		
}

function  reply_msg($replyToken,$msg){
	$setting = require('/var/www/html/bom/line/settings.php');
        $channelId = $setting['channelId'];
        $channelSecret = $setting['channelSecret'];
        $channelMid = $setting['channelMid'];
        $accessToken = $setting['accessToken'];
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
	$response = $bot->replyMessage($replyToken, $textMessageBuilder);
	$res_status = $response->getHTTPStatus() . ' ' . $response->getRawBody();
	return $msg;
}

function  register_form($mid,$msg,$member_id,$line_id){
	$setting = require('/var/www/html/bom/line/settings.php');
        $channelId = $setting['channelId'];
        $channelSecret = $setting['channelSecret'];
        $channelMid = $setting['channelMid'];
        $accessToken = $setting['accessToken'];
	$confirm_msg = '#123confirm:'.$member_id.':'.$line_id;
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
	//$image_url = 'https://s3-us-west-1.amazonaws.com/powr/defaults/image-slider2.jpg';
	$image_url = 'https://www.9tana.com/wp-content/uploads/2016/03/3bb-620x270.jpg';
	$actBuilderd = array();
	//$actBuilder[] = new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('Yes','ระบบทำการลงทะเบียนให้ท่านแล้ว');
	//$actBuilder[] = new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('No','ขอบคุณที่ใช้บริการ');
	$actBuilder[] = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('YES',$confirm_msg);
	$actBuilder[] = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('NO','#123cancle');
	$templateBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder('คุณต้องการลงทะเบียนกับข้อมูลดังต่อไปนี้หรือไม่',$msg, $image_url, $actBuilder);
	$builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('ลงทะเบียนใช้งาน', $templateBuilder);
	$response = $bot->pushMessage($mid, $builder);
	return $msg;
}

function  register($mid,$line_id,$member_id){	
	$objConnect = mysqli_connect("10.10.19.138","webbom","bombom") or die(mysql_error());
    	mysqli_select_db($objConnect,"issue_nw");		
	$strSQL = "UPDATE member SET mid = '$mid',line_id = '$line_id' WHERE member_id = '$member_id'";
    	$objQuery = mysqli_query($objConnect,$strSQL) or die (mysql_error());
    	$no = mysqli_num_rows($objQuery);
	if ($no > 0) {
		$strSQL = "";
		$objQuery = mysqli_query($objConnect,$strSQL) or die (mysql_error());	
		while($obResult = mysqli_fetch_array($objQuery,MYSQLI_ASSOC)) {
		
		}
	}
}

function  check_info($mid){
	$msg = 'ERROR';
	$setting = require('/var/www/html/bom/line/settings.php');
	$channelId = $setting['channelId'];
	$channelSecret = $setting['channelSecret'];
	$channelMid = $setting['channelMid'];
	$accessToken = $setting['accessToken'];

	$objConnect = mysqli_connect("10.10.19.138","webbom","bombom") or die(mysql_error());
	mysqli_set_charset($objConnect, "utf8");
	mysqli_select_db($objConnect,"svgarena");		
	$strSQL = "SELECT member_id FROM users WHERE mid = '$mid'";
	$objQuery = mysqli_query($objConnect,$strSQL) or die (mysql_error());
	$no = mysqli_num_rows($objQuery);
	if ($no > 0) {
		while($obResult = mysqli_fetch_array($objQuery,MYSQLI_ASSOC)) {
			$member_id = $obResult['member_id'];
			$res_msg = 'IDของท่านได้ทำการลงทะเบียนใช้งานแล้ว';
			$strSQL2 = "SELECT member_id,firstname,lastname FROM users WHERE member_id = '$member_id'";
			$objQuery2 = mysqli_query($objConnect,$strSQL2) or die (mysql_error());
			$no2 = mysqli_num_rows($objQuery2);
			while($obResult2 = mysqli_fetch_array($objQuery2,MYSQLI_ASSOC)) {
				if (isset($obResult2['member_id'])) {
					$res_info = $obResult2["member_id"]." ".$obResult2["firstname"]." ".$obResult2["lastname"];
				}
			}
			$msg = "$res_info\n"."$res_msg";
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
			$response = $bot->pushMessage($mid, $textMessageBuilder);
		}
	} else {	
		$msg = "ท่านยังไม่ได้ทำการลงทะเบียนการใช้งานโปรดทำการลงทะเบียนโดยการพิม REGISTER:<รหัสพนักงานตัวพิมใหญ่>:<Line ID>";
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
		$response = $bot->pushMessage($mid, $textMessageBuilder);
	}

	mysqli_close($objConnect);
	return $msg;
}

function write_file($txt){
	$myfile = fopen("log.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $txt);
	fclose($myfile);
}
?>

