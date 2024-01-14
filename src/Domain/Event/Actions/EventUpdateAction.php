<?php

namespace src\Domain\Event\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use src\Domain\Event\DTO\EventUpdateData;
use src\Domain\Event\Models\Event;

class EventUpdateAction extends Model
{
    public static function execute(Event $event, EventUpdateData $data):void
    {
        $event->update([
            ...$data->all(),
            'sent' => 0,
            'date' => Carbon::parse($data->date)
        ]);

        if($event['mentors_training']) {
            EventRemoveMentorTrainingFlagAction::fromAllExcept($event);
        }

        if($event['mentees_applying']) {
            EventRemoveMenteeApplyingFlagAction::fromAllExcept($event);
        }
    }
}
