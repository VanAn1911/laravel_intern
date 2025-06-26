<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

//Tự đông đăng ký các route xác thực
Auth::routes();

Route::get('/', function () {
    return view('home');
})->name('home');
Route::middleware(['guest'])->group(function () {
    // Đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Đăng ký tài khoản
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    
    // Quên mật khẩu
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Đặt lại mật khẩu
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
}); 

  Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/posts', function () {
        return view('posts.index');
    })->name('posts.index');
    
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
  });

// Route::get('/send-email', function () {
//     $email_message = 'Kiểm tra gửi Mail từ Laravel!';
//     Mail::to('recipient@example.com')->send(new SendEmail($email_message));
//     return "Email đã được gửi!";
// });

// //Callback Funtion: Trả về dữ liệu trực tiếp từ route
// Route::get('/callback', function () {
//     return "Xin Chào";
// });

// //Route::view: Trả trực tiếp về view
// Route::get('/home', function () {
//     return view('auth.login');
// });

// //Controller: Sử dụng controller để xử lý logic
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\PostController;

// // Gắn route với controller và phương thức
// Route::get('/users', [UserController::class, 'index']);
// Route::get('/users/{id}', [UserController::class, 'show']);

// //Route Parameters: Truyền tham số vào route
// // Tham số bắt buộc: {id} phải được truyền vào URL
// Route::get('/user/{id}', function ($id) {
//     return 'ID người dùng: ' . $id;
// });

// // Tham số tùy chọn: {name?} có thể không truyền
// Route::get('/greeting/{name?}', function ($name = 'Khách') {
//     return 'Xin chào, ' . $name;
// });


// // Route Group: Nhóm các route có cùng tiền tố
// // Route group với prefix và resource controller
// Route::prefix('admin')->group(function () {
//     // Resource controller tự động tạo các route CRUD
//     Route::resource('posts', PostController::class);
// });

// //Truyền dữ liệu vào view
// Route::get('/demo', function () {
//     $name = "An";
//     $users = [
//             (object)['name' => 'An'],
//             (object)['name' => 'Bình'],
//             (object)['name' => 'Chi'],
//         ];
//     return view('example', compact('name', 'users'));
// });







