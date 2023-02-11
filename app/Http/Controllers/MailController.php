<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MailController extends Controller
{
    public function verify($key): RedirectResponse
    {
        $mentor = Mentor::query()->where('key', $key);
        $mentor->update(['status' => 1]);
        $mentor = $mentor->first();

        Mail::create([
            'mentor_ids' => json_encode(array($mentor->id)),
            'student_ids' => null,
            'content' => null,
            'type' => 'verificationPassed'
        ]);

        return Redirect::route('home');
    }

    public function sendCustom(Request $request){
        $data = $request->validate([
            'content' => 'required',
            'receivers' => 'required'
        ]);

        $email = [];
        $email['type'] = 'custom';
        $email['content'] = $data['content'];

        switch ($data['receivers']['type']){
            case 'mentors':
                $email['mentor_ids'] = json_encode($data['receivers']['id']);
                $email['student_ids'] = null;
                break;
            case 'students':
                $email['student_ids'] = json_encode($data['receivers']['id']);
                $email['mentor_ids'] = null;
                break;
        }

        Mail::create($email);
    }
}
