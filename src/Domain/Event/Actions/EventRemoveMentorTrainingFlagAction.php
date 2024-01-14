<?php

namespace src\Domain\Event\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Event\Models\Event;

class EventRemoveMentorTrainingFlagAction extends Model
{
    public static function fromAllExcept(Event $event):void
    {
        if($event['mentors_training']){
            Event::query()->whereNot('id', $event['id'])->update([
                'mentors_training' => 0,
            ]);
        }
    }
}
