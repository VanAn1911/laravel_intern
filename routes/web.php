<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('home');
});
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

Route::get('/send-email', function () {
    try {
        Mail::to('recipient@example.com')->send(new SendEmail());
        return "Email đã được gửi!";
    } catch (\Exception $e) {
        return "Gửi email thất bại: " . $e->getMessage();
    }
});
