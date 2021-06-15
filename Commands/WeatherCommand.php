<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\TelegramLog;

class WeatherCommand extends UserCommand
{
    protected $name = 'weather';

    protected $description = 'Показывает погоду по расположению';

    protected $usage = '/weather <location>';

    protected $version = '1.0';

    private $owm_api_base_uri = 'http://api.openweathermap.org/data/2.5/';

    private function getWeatherData($location): string
    {
        $client = new Client(['base_uri' => $this->owm_api_base_uri]);
        $path   = 'weather';
        $query  = [
            'q'     => $location,
            'units' => 'metric',
            'APPID' => trim($this->getConfig('owm_api_key')),
            'lang'  => 'ru',
        ];

        try {
            $response = $client->get($path, ['query' => $query]);
        } catch (RequestException $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }

        return (string) $response->getBody();
    }

    private function getWeatherString(array $data): string
    {
        try {
            if (!(isset($data['cod']) && $data['cod'] === 200)) {
                return '';
            }

            $conditions     = [
                'clear'        => ' ☀️',
                'clouds'       => ' ☁️',
                'rain'         => ' ☔',
                'drizzle'      => ' ☔',
                'thunderstorm' => ' ⚡️',
                'snow'         => ' ❄️',
            ];
            $conditions_now = strtolower($data['weather'][0]['main']);

            return sprintf(
                'Температура в %s (%s) %s°C' . PHP_EOL .
                'Погодные условия: %s%s',
                $data['name'],
                $data['sys']['country'],
                $data['main']['temp'],
                $data['weather'][0]['description'],
                $conditions[$conditions_now] ?? ''
            );
        } catch (Exception $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }
    }

    public function execute(): ServerResponse
    {
        $owm_api_key = $this->getConfig('owm_api_key');
        if (empty($owm_api_key)) {
            return $this->replyToChat('OpenWeatherMap API не определён.');
        }

        $location = trim($this->getMessage()->getText(true));
        if ($location === '') {
            return $this->replyToChat('Уточните что вы ищете: ' . $this->getUsage());
        }

        $text = 'Не могу найти погоду для ' . $location;
        if ($weather_data = json_decode($this->getWeatherData($location), true)) {
            $text = $this->getWeatherString($weather_data);
        }
        return $this->replyToChat($text);
    }
}