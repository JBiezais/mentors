<?php

namespace src\Domain\Event\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Event\Models\Event;

class EventDeleteAction extends Model
{
    public static function execute(Event $event):void
    {
        $event->delete();
    }
}
