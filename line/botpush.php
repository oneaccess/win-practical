<?php
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/ActionType.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/EventSourceType.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/HTTPHeader.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/MessageType.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/Meta.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/TemplateType.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/HTTPClient.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/HTTPClient/Curl.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/HTTPClient/CurlHTTPClient.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/MessageBuilder.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/MessageBuilder/TextMessageBuilder.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Response.php');

        $channel_access_token = 'vJkaEx7B0tYK6EtRxhYjBk70iDtSq5VYVT6+orl5AuqX82iChMQQUMyywaE2V5CNuY5dCRXrozUlssJQTWxxqpwj9lfXuF/IHWtttqi+HTQoiCj6tgc5Ijk+85l/Qdq2/z4llNHwMBh+11zXzJ1LAwdB04t89/1O/w1cDnyilFU=';
        $channel_secret = '800277791d946cc4e2847fbe2b48578e';
        $to = 'U8ce89a9c5318ef4fbbeef105ca1bf201';
        $msg = 'hello';

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channel_access_token);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
        $response = $bot->pushMessage($to, $textMessageBuilder);

        echo $response->getHTTPStatus() . ' ' . $response->getRawBody()."\n";

        print_r($response);

?>
