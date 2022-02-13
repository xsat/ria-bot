<?php

declare(strict_types=1);

namespace App\Telegram;

use App\Entity\Realty;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Response;

class BotApi
{
    private ClientInterface $client;

    private string $token;

    private string $chatId;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->token = $_SERVER['TELEGRAM_TOKEN'] ?? '';
        $this->chatId = $_SERVER['TELEGRAM_CHAT_ID'] ?? '';
    }

    public function sendRealty(Realty $realty): bool
    {
        if (empty($this->token) || empty($this->chatId) || empty($realty->getData())) {
            return false;
        }

        $data = $realty->getData();
        $items = [];

        if (!empty($data['currency_type_uk']) && !empty($data['price'])) {
            $items[] = "<b>Ціна</b>: {$data['currency_type_uk']}{$data['price']}";
        }

        if (!empty($data['publishing_date'])) {
            $items[] = "<b>Дата</b>: {$data['publishing_date']}";
        }

        if (!empty($data['district_name_uk'])) {
            $items[] = "<b>Область</b>: {$data['district_name_uk']}";
        }

        if (!empty($data['state_name_uk'])) {
            $items[] = "<b>Район</b>: {$data['state_name_uk']}";
        }

        if (!empty($data['city_name_uk'])) {
            $items[] = "<b>Місто</b>: {$data['city_name_uk']}";
        }

        if (!empty($data['description_uk'])) {
            $items[] = $data['description_uk'];
        }

        if (!empty($data['beautiful_url'])) {
            $items[] = "<a href=\"https://dom.ria.com/uk/{$data['beautiful_url']}\">Подивитися на сайті</a>";
        }

        return
            Response::HTTP_OK === $this->client->post(
                "https://api.telegram.org/bot{$this->token}/sendPhoto",
                [
                    'json' => [
                        'chat_id' => $this->chatId,
                        'caption' => implode(PHP_EOL . PHP_EOL, $items),
                        'parse_mode' => 'HTML',
                        'photo' => 'https://cdn.riastatic.com/photos/' .
                            str_replace(
                                '.jpg',
                                'f.jpg',
                                $data['main_photo']
                            )
                    ],
                ]
            )->getStatusCode();
    }
}