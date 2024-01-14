<?php

namespace src\Domain\Event\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Event\DTO\EventCreateData;
use src\Domain\Event\Models\Event;

class EventCreateAction extends Model
{
    public static function execute(EventCreateData $data):void
    {
        /** @var Event $event */
        $event = Event::query()->create($data->toArray());

        if($event['mentors_training']) {
            EventRemoveMentorTrainingFlagAction::fromAllExcept($event);
        }

        if($event['mentees_applying']) {
            EventRemoveMenteeApplyingFlagAction::fromAllExcept($event);
        }
    }
}
