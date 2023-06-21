<?php

namespace App\Http\Controllers;

use App\Jobs\RegistrationEmailJob;
use App\Mail\RegistrationMail;
use Illuminate\Http\Request;
use Mail;

class TestController extends Controller
{
    public function basic_email() {
        $details = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp',
            'email' => 'ahmadqalbi2@gmail.com'
        ];

        dispatch(new RegistrationEmailJob($details));
        echo "Basic Email Sent. Check your inbox.";
    }
}
