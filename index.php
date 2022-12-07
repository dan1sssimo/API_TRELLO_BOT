<?php

include("controller/TelegramBotController.php");
include("model/DB.php");
include("model/TelegramBot.php");


$telegramBot = new \botController\TelegramBotController("sendMessage");
$telegramBot->helloMessage();
