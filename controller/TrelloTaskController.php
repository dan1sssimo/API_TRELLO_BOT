<?php

namespace trelloTask;


class TrelloTaskController
{
    protected const TELEGRAM_TOKEN = '5637339021:AAHTxx6TDOm0Cu_tFI9Df2EWpGFxBejS_Kc';

    protected const TELEGRAM_API_URL = 'https://api.telegram.org/bot' . self::TELEGRAM_TOKEN . '/';

    protected $fetchData;

    protected $chatID;

    protected $requestMethod;

    public function __construct($requestMethod, $chatID)
    {
        $this->requestMethod = $requestMethod;
        $this->chatID = $chatID;
    }

    public function sendChangesFromTrello(): void
    {
        $this->fetchData = json_decode(file_get_contents('php://input'), true);
        $this->fetchData = $this->fetchData['action'];
        $actionCardName = $this->fetchData["data"]['card']["name"];
        $fromColumn = $this->fetchData["display"]["entities"]["listBefore"]['text'];
        $toColumn = $this->fetchData["display"]["entities"]["listAfter"]['text'];
        $userTrelloName = $this->fetchData["memberCreator"]['fullName'];
        if ($actionCardName) {
            $sendData = [
                "chat_id" => $this->chatID,
                "text" => "User " . $userTrelloName . " moved card " . $actionCardName . " from column " . $fromColumn . " to column " . $toColumn
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => self::TELEGRAM_API_URL . $this->requestMethod,
                CURLOPT_POSTFIELDS => http_build_query($sendData),
            ));
            curl_exec($curl);
            curl_close($curl);
        }
    }
}

$trelloTaskModel = new TrelloTaskController('sendMessage', '-512521066');
$trelloTaskModel->sendChangesFromTrello();