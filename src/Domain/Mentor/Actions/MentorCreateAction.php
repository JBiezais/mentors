<?php

namespace src\Domain\Mentor\Actions;

use Illuminate\Http\UploadedFile;
use src\Domain\Mail\Actions\MailVerificationCreateAction;
use src\Domain\Mentor\DTO\MentorCreateData;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Shared\Actions\UploadFileAction;

class MentorCreateAction
{
    public static function execute(MentorCreateData $data, ?UploadedFile $file):void
    {
        $img = null;
        if($file){
            $img = (new UploadFileAction())->upload($file);
        }

        $mentor = Mentor::query()->create([
            ...$data->all(),
            'img' => $img
        ]);

        MailVerificationCreateAction::execute([$mentor->id]);
    }
}
