<?php

require "vendor/autoload.php";
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;
use LINE\LINEBot\Message\MultipleMessages;
use LINE\LINEBot\Message\RichMessage\Markup;
error_reporting(-1);
$setting = require('settings.php');
$channelId = $setting['channelId'];
$channelSecret = $setting['channelSecret'];
$channelMid = $setting['channelMid'];
$accessToken = $setting['accessToken'];
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
 
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $userMessage = strtolower($userMessage);
    $config = [
                        'channelId' => $channelId,
                        'channelSecret' => $channelSecret,
                        'channelMid' => $replyToken,
                ];


                $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
                $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($userMessage);
                $response = $bot->pushMessage($targetMid, $textMessageBuilder);
                echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
}
?>
