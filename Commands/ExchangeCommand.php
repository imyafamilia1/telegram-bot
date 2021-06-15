<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\TelegramLog;

class ExchangeCommand extends UserCommand
{
    protected $name = 'exchange';

    protected $description = 'Показывает курс';

    protected $usage = '/exchange';

    protected $version = '1.0';

    private function getExchangeRateData() : \SimpleXMLElement
    {
        try {
            $date = date('d/m/Y');
            $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req={$date}";
            $xml = simplexml_load_file($url);
        } catch (Exception $e) {
            TelegramLog::error($e->getMessage());

            return new SimpleXMLElement('');
        }

        return $xml;
    }

    private function getExchangeRateString(array $data) : string
    {
        try {
            $date = date('d/m/Y');
            return sprintf(
                'По данным центробанка на %s курс составляет:' . PHP_EOL .
                'Доллар к рублю - %s' . PHP_EOL .
                'Евро к рублю - %s',
                $date,
                $data['Valute']['10']['Value'],
                $data['Valute']['11']['Value'],
            );
        } catch (Exception $e) {
            TelegramLog::error($e->getMessage());

            return '';
        }
    }

    public function execute(): ServerResponse
    {
        if ($exchange_rate_data = json_encode($this->getExchangeRateData(), true)) {
            $exchange_rate_data = json_decode($exchange_rate_data,TRUE);
            $text = $this->getExchangeRateString($exchange_rate_data);
        }
        return $this->replyToChat($text);
    }
}