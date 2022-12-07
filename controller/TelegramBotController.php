<?php

namespace botController;

use telegramBot\TelegramBot;

class TelegramBotController
{
    protected const TELEGRAM_TOKEN = '5637339021:AAHTxx6TDOm0Cu_tFI9Df2EWpGFxBejS_Kc';

    protected const TELEGRAM_API_URL = 'https://api.telegram.org/bot' . self::TELEGRAM_TOKEN . '/';

    protected $botModel;

    protected $fetchData;

    protected $userID;

    protected $userName;

    protected $requestMethod;

    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        $this->botModel = new TelegramBot();
    }

    public function helloMessage(): void
    {
        $this->fetchData = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
        $this->fetchData = $this->fetchData['message'];
        if ($this->fetchData['text'] == '/start') {
            $this->userName = $this->fetchData['from']['first_name'] . (!empty($this->fetchData['from']['last_name']) ? ' ' . $this->fetchData['from']['last_name'] : false);
            $this->userID = $this->fetchData['from']['id'];
            $this->botModel->insertUser("users", $this->userID, $this->userName);
            $sendData = [
                'text' => 'Привіт ' . $this->userName,
                'chat_id' => $this->fetchData['chat']['id']
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => self::TELEGRAM_API_URL . $this->requestMethod,
                CURLOPT_POSTFIELDS => http_build_query($sendData),
            ]);
            curl_exec($curl);
            curl_close($curl);
        }
    }
}