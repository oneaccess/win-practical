<?php
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/Constant/ActionType.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/Constant/EventSourceType.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/Constant/HTTPHeader.php');
        require_once('vendor/linecorp/line-bot-sdk//src/LINEBot/Constant/MessageType.php');
        require_once('vendor/linecorp/line-bot-sdksrc/LINEBot/Constant/Meta.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/Constant/TemplateType.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/HTTPClient.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/HTTPClient/Curl.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/HTTPClient/CurlHTTPClient.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/MessageBuilder.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php');
        require_once('vendor/linecorp/line-bot-sdk/src/LINEBot/Response.php');

        $channel_access_token = 'vJkaEx7B0tYK6EtRxhYjBk70iDtSq5VYVT6+orl5AuqX82iChMQQUMyywaE2V5CNuY5dCRXrozUlssJQTWxxqpwj9lfXuF/IHWtttqi+HTQoiCj6tgc5Ijk+85l/Qdq2/z4llNHwMBh+11zXzJ1LAwdB04t89/1O/w1cDnyilFU=';
        $channel_secret = '800277791d946cc4e2847fbe2b48578e';
        $to = 'U8ce89a9c5318ef4fbbeef105ca1bf201';
        $userId = 'Uf34420370c875a3540bd79f148c915c2';
        $msg = 'hello';

         $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channel_access_token);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);
        $response = $bot->getProfile($userId);
        if ($response->isSucceeded()) {
                $profile = $response->getJSONDecodedBody()."\n";
                echo $profile['displayName']."\n";
                echo $profile['pictureUrl']."\n";
                echo $profile['statusMessage']."\n";
        }


        print_r($response);

?>
