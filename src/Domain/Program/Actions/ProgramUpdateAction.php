<?php

namespace src\Domain\Program\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Program\DTO\ProgramUpdateData;
use src\Domain\Program\Models\Program;

class ProgramUpdateAction extends Model
{
    public static function execute(Program $program, ProgramUpdateData $data):void
    {
        $program->update($data->all());
    }
}
