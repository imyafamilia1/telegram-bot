<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

try {
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['username']);

    $telegram->addCommandsPaths($config['commands']['paths']);

    $telegram->enableLimiter($config['limiter']);

    // почему-то не работает через config.php, так что расположил здесь
    $telegram->setCommandConfig('weather', ['owm_api_key' => '']);

    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    error_log($e->getMessage());
}