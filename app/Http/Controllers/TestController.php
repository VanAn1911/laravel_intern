<?php
namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;

class TestController extends Controller
{
    public function lazy()
    {
        Debugbar::startMeasure('lazy-loop', 'Lazy loading xử lý');

        $users = User::all();              // 1 query
        foreach ($users as $user) {
            $posts = $user->posts;         // N thêm query mỗi user
        }

        Debugbar::stopMeasure('lazy-loop');
        return view('test', compact('users'));
    }

    public function eager()
    {
        Debugbar::startMeasure('eager-loop', 'Eager loading xử lý');

        $users = User::all(); // 1 query
        $users->load('posts'); // 1 query lấy toàn bộ posts liên quan
        foreach ($users as $user) {
            $posts = $user->posts;            // không sinh thêm query
        }

        Debugbar::stopMeasure('eager-loop');
        return view('test', compact('users'));
    }

}
