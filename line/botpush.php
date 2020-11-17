<?php



require "vendor/autoload.php";

$access_token = 'vJkaEx7B0tYK6EtRxhYjBk70iDtSq5VYVT6+orl5AuqX82iChMQQUMyywaE2V5CNuY5dCRXrozUlssJQTWxxqpwj9lfXuF/IHWtttqi+HTQoiCj6tgc5Ijk+85l/Qdq2/z4llNHwMBh+11zXzJ1LAwdB04t89/1O/w1cDnyilFU=';

$channelSecret = '800277791d946cc4e2847fbe2b48578e';

$pushID = 'Uf34420370c875a3540bd79f148c915c2';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
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
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($userMessage);
    //$response = $bot->pushMessage($pushID, $textMessageBuilder);
    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
}
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();


