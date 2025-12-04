<?php

namespace src\Domain\Event\Actions;

use src\Domain\Event\Models\Event;

class EventRemoveMenteeApplyingFlagAction
{
    public static function fromAllExcept(Event $event):void
    {
        Event::query()->whereNot('id', $event['id'])->update([
            'mentees_applying' => 0,
        ]);
    }
}
