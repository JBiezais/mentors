<?php

namespace src\Domain\Shared\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class UserNotificationCreateAction extends Model
{
    public static function execute(string $title, string $message):void
    {
        Session::flash('message', ['title' => $title, 'text' => $message]);

    }
}
