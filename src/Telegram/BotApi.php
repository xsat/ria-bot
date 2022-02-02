<?php

declare(strict_types=1);

namespace App\Telegram;

use App\Entity\Realty;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

        $caption = "<b>Ціна</b>: {$data['currency_type_uk']}{$data['price']}

<b>Дата</b>: {$data['publishing_date']}

<b>Область</b>: {$data['state_name_uk']}

<b>Район</b>: {$data['district_name_uk']}

<b>Місто</b>: {$data['city_name_uk']}

{$data['description_uk']}

<a href='https://dom.ria.com/uk/{$data['beautiful_url']}'>Подивитися на сайті</a>";

        return Response::HTTP_OK === $this->client->post(
                "https://api.telegram.org/bot{$this->token}/sendPhoto",
                [
                    'json' => [
                        'chat_id' => $this->chatId,
                        'caption' => $caption,
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