<?php

namespace src\Domain\Event\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Event\Models\Event;

class EventRemoveMenteeApplyingFlagAction extends Model
{
    public static function fromAllExcept(Event $event):void
    {
        Event::query()->whereNot('id', $event['id'])->update([
            'mentees_applying' => 0,
        ]);
    }
}
