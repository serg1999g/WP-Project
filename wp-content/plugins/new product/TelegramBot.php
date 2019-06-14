<?php

use GuzzleHttp\Client;

class TelegramBot
{
    protected $token = "857000324:AAED8gv1jOXdt5YR4PsdjBjUMeRFC7PtQU4";

    protected $updateId;

    protected function query($method, $params = [])
    {
        $url = "https://api.telegram.org/bot";

        $url .= $this->token;

        $url .= "/" . $method;

        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }

        $client = new Client([
            'base_uri' => $url
        ]);

        $result = $client->request('GET');

        return json_decode($result->getBody());
    }

    public function sendMessage($chat_id, $text)
    {
        $response = $this->query('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text
        ]);
        return $response;
    }
}
