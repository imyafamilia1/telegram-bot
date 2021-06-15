<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class StartCommand extends SystemCommand
{
    protected $name = 'start';

    protected $description = 'Начальная команда';

    protected $usage = '/start';

    protected $version = '1.0';

    protected $private_only = true;

    public function execute(): ServerResponse
    {
        return $this->replyToChat(
            'Привет! Это тестовый бот с небольшим функционалом.'
        );
    }
}