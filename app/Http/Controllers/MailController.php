<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use src\Domain\Mail\Actions\MailCustomCreateAction;
use src\Domain\Mail\Actions\MailVerificationPassedCreateAction;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Shared\Actions\UserNotificationCreateAction;

class MailController extends Controller
{
    public function verify($key): RedirectResponse
    {
        $mentor = Mentor::query()->where('key', $key)->first();
        $mentor->update(['status' => 1]);

        MailVerificationPassedCreateAction::execute([$mentor->id]);

        UserNotificationCreateAction::execute(
            'Verifikācija apstiprināta',
            'Jūsu pieteikums ir veiksmīgi nosūtīts lūdzu gaidiet turpmāko ziņu e-pastā'
        );

        return Redirect::route('home');
    }

    public function sendCustom(Request $request): void
    {
        $data = $request->validate([
            'content' => 'required',
            'receivers' => 'required'
        ]);

        MailCustomCreateAction::execute(
            $data['content'],
            $data['receivers']['type'],
            $data['receivers']['id']
        );
    }
}
