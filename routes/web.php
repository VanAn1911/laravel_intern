<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;

//Tự đông đăng ký các route xác thực
Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

  Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/posts', function () {
        return view('posts.index');
    })->name('posts.index');

  });

Route::get('/send-email', function () {
    $email_message = 'Kiểm tra gửi Mail từ Laravel!';
    Mail::to('recipient@example.com')->send(new SendEmail($email_message));
    return "Email đã được gửi!";
});

//Callback Funtion: Trả về dữ liệu trực tiếp từ route
Route::get('/callback', function () {
    return "Xin Chào";
});

//Route::view: Trả trực tiếp về view
Route::get('/home', function () {
    return view('auth.login');
});

//Controller: Sử dụng controller để xử lý logic
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

// Gắn route với controller và phương thức
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

//Route Parameters: Truyền tham số vào route
// Tham số bắt buộc: {id} phải được truyền vào URL
Route::get('/user/{id}', function ($id) {
    return 'ID người dùng: ' . $id;
});

// Tham số tùy chọn: {name?} có thể không truyền
Route::get('/greeting/{name?}', function ($name = 'Khách') {
    return 'Xin chào, ' . $name;
});

//Route name: Đặt tên cho route để dễ dàng tham chiếu
Route::get('/profile', [UserController::class, 'show'])->name('user.profile');

// Route Group: Nhóm các route có cùng tiền tố
// Route group với prefix và resource controller
Route::prefix('admin')->group(function () {
    // Resource controller tự động tạo các route CRUD
    Route::resource('posts', PostController::class);
});

//Truyền dữ liệu vào view
Route::get('/demo', function () {
    $name = "An";
    $users = [
            (object)['name' => 'An'],
            (object)['name' => 'Bình'],
            (object)['name' => 'Chi'],
        ];
    return view('example', compact('name', 'users'));
});







