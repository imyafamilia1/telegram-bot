<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

try {
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

    $result = $telegram->deleteWebhook();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    error_log($e->getMessage());
}