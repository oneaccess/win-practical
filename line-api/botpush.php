<?php
namespace main;

defined('BASEPATH') OR exit('No direct script access allowed');
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
require_once ('vendor/autoload.php');
require_once ('bot_settings.php');
require_once ('vendor/linecorp/line-bot-sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php');
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");

///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
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
