<?php

namespace webHooks;

class WebHookController
{
    protected const TELEGRAM_TOKEN = '5637339021:AAHTxx6TDOm0Cu_tFI9Df2EWpGFxBejS_Kc';

    protected const API_TOKEN_TRELLO = "8239e13a2173f1619ad38f6e16058bff516ae3ac96270149d3a6561bfca68a60";

    protected const SECRET_TOKEN_TRELLO = "dc34b8ae73fb67dc46a6e9f32ff6bce2";

    protected const TABLE_ID = "6390d26101d7890092fe1238";

    protected $listID;

    public function createList($listName): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.trello.com/1/boards/' . self::TABLE_ID . '/lists?',
            CURLOPT_POSTFIELDS => http_build_query([
                "name" => $listName,
                "key" => self::SECRET_TOKEN_TRELLO,
                "token" => self::API_TOKEN_TRELLO,
            ]),
        ));
        $respose = json_decode(curl_exec($curl));
        $this->listID = (((array)$respose)['id']);
        curl_close($curl);
    }

    public function deleteList($listID): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_HEADER => 0,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.trello.com/1/lists/' . $listID . '/closed?',
            CURLOPT_POSTFIELDS => http_build_query([
                "value" => true,
                "key" => self::SECRET_TOKEN_TRELLO,
                "token" => self::API_TOKEN_TRELLO,
            ]),
        ));
        curl_exec($curl);
        curl_close($curl);
    }

    public function getLists(): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.trello.com/1/boards/" . self::TABLE_ID . "/lists?key=" . self::SECRET_TOKEN_TRELLO .
                "&token=" . self::API_TOKEN_TRELLO,
        ));
        $data = json_decode(curl_exec($curl));
        curl_close($curl);
        foreach ($data as $item) {
            $array = (array)$item;
            $this->deleteList($array['id']);
        }
    }

    public function setHookTrello(): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.trello.com/1/webhooks/?',
            CURLOPT_POSTFIELDS => http_build_query([
                "callbackURL" => "https://bot.savchenkoportfolio.fun/trello",
                "idModel" => $this->listID,
                "key" => self::SECRET_TOKEN_TRELLO,
                "token" => self::API_TOKEN_TRELLO,
            ]),
        ));
        curl_exec($curl);
        curl_close($curl);
    }

    public function setHookTelegram(): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.telegram.org/bot" . self::TELEGRAM_TOKEN . "/setWebhook",
            CURLOPT_POSTFIELDS => http_build_query(["url" => "https://bot.savchenkoportfolio.fun/"]),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}

$webHookModel = new WebHookController();

$webHookModel->getLists();
$webHookModel->createList('InProgress');
$webHookModel->createList('Done');
$webHookModel->setHookTrello();
$webHookModel->setHookTelegram();