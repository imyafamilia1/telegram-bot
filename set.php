<?php
require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

try {
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['username']);

    $result = $telegram->setWebhook($config['hook_url']);
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    error_log($e->getMessage());
}