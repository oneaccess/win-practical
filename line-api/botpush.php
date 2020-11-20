<?php



require "vendor/autoload.php";
require_once ('bot_settings.php');

//$access_token = 'vJkaEx7B0tYK6EtRxhYjBk70iDtSq5VYVT6+orl5AuqX82iChMQQUMyywaE2V5CNuY5dCRXrozUlssJQTWxxqpwj9lfXuF/IHWtttqi+HTQoiCj6tgc5Ijk+85l/Qdq2/z4llNHwMBh+11zXzJ1LAwdB04t89/1O/w1cDnyilFU=';

//$channelSecret = '800277791d946cc4e2847fbe2b48578e';

//$pushID = 'U8ce89a9c5318ef4fbbeef105ca1bf201';

//$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
//$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_MESSAGE_CHANNEL_SECRET]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();







