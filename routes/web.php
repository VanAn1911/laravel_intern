<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Mews\Purifier\Facades\Purifier;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\UserController;

//Tự đông đăng ký các route xác thực
  Auth::routes(); //còn logout vẫn sài

  Route::get('/news', [NewsController::class, 'index'])->name('news.index');
  Route::get('/news/{post:slug}', [NewsController::class, 'show'])->name('news.show');//truyền thẳng post

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
    // Quản lý bài viết
    //Route::get('/posts', function () {return view('posts.index');})->name('posts.index'); 
    Route::get('/posts/data', [PostController::class, 'data'])->name('posts.data'); //dùng để lấy dữ liệu cho DataTables
    Route::resource('posts', PostController::class); //đặt tên tham số là post thay vì posts
    Route::delete('posts-delete-all', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
    
    // Câp nhật thông tin cá nhân
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
  });

  Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/posts/data', [AdminPostController::class, 'data'])->name('posts.data');
    Route::resource('posts', AdminPostController::class);
    Route::resource('users', UserController::class);
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

//Truyền dữ liệu vào view
Route::get('/demo', function (Request $request) {
    $safeTitle = Purifier::clean($request->query('title'));//sử dụng Purifier để làm sạch dữ liệu đầu vào
    $name = "An";
    $users = [
            (object)['name' => 'An'],
            (object)['name' => 'Bình'],
            (object)['name' => 'Chi'],
        ];
    return view('example', compact('name', 'users', 'safeTitle'));
})->name('demo');









