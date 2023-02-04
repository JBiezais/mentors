<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\Mail;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MailController extends Controller
{
    public function verify($key): RedirectResponse
    {
        $mentor = Mentor::query()->where('key', $key);
        $mentor->update(['status' => 1]);
        $mentor = $mentor->first();
//        dd($mentor);
        Mail::create([
            'mentor_ids' => json_encode(array($mentor->id)),
            'student_ids' => null,
            'content' => null,
            'type' => 'verificationPassed'
        ]);

        return Redirect::route('home');
    }
}
