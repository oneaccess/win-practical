<?php

require "vendor/autoload.php";
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;
use LINE\LINEBot\Message\MultipleMessages;
use LINE\LINEBot\Message\RichMessage\Markup;
error_reporting(-1);
        $setting = require('bot_settings.php');
        $channelId = $setting['channelId'];
        $channelSecret = $setting['channelSecret'];
        $channelMid = $setting['channelMid'];
        $accessToken = $setting['accessToken'];

$config = [
                        'channelId' => $channelId,
                        'channelSecret' => $channelSecret,
                        'channelMid' => $channelMid,
                ];


                $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
                $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
                $response = $bot->pushMessage($targetMid, $textMessageBuilder);
                echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
