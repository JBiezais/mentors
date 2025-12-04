<?php

namespace src\Domain\Shared\Actions;

use Illuminate\Support\Facades\Session;

class UserNotificationCreateAction
{
    public static function execute(string $title, string $message):void
    {
        Session::flash('message', ['title' => $title, 'text' => $message]);

    }
}
