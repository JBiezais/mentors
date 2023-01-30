<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MailController extends Controller
{
    public function verify($key): RedirectResponse
    {
        Mentor::query()->where('key', $key)->update(['status' => 1]);

        return Redirect::route('home');
    }
}
