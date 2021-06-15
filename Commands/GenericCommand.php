<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class GenericCommand extends SystemCommand
{
    protected $name = 'generic';

    protected $description = 'Работает с общими командами';

    protected $version = '1.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        return $this->replyToChat("Команда /{$command} не найдена... :(");
    }
}