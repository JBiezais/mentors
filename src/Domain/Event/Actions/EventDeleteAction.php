<?php

namespace src\Domain\Event\Actions;

use src\Domain\Event\Models\Event;

class EventDeleteAction
{
    public static function execute(Event $event):void
    {
        $event->delete();
    }
}
