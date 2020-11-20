<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
require_once ('vendor/autoload.php');
require_once ('bot_settings.php');
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
class Bot extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);
 
        //recieve data from LINE Messaging API
        $content = file_get_contents('php://input');
 
        //decode json to array
        $events = json_decode($content, true);
        
        //get reply token and message if events is not null
        if (!is_null ($events)) {
            $replyToken = $events['events'][0]['replyToken'];
            $message = $events['events'][0]['message']['text'];
        }
        
        //condition to reply message
        if (preg_match("/ชื่ออะไร/", $message)) {
            $reply = "ชื่อปูเป้จ้าา";
        }
        else {
            $reply = "สวัสดีจ้า";
        }
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($reply);
$response = $bot->replyMessage($replyToken,$textMessageBuilder);
        if ($response->isSucceeded()) {
            echo 'Succeeded!';
            return;
        }
        
        // Failed
        echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
}
?>
