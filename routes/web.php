<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

Route::get('/', function () {
    return view('home');
});


Route::get('/send-email', function () {
    $email_message = 'Kiểm tra gửi Mail từ Laravel!';
    Mail::to('recipient@example.com')->send(new SendEmail($email_message));
    return "Email đã được gửi!";
});
