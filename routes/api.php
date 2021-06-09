<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'register']);
Route::get('login', [UserController::class, 'login']);
Route::get("posts", [PostController::class, 'index'])->name('postlist');
// getting post with comments
Route::get('/show/{id}', [PostController::class, 'show']);

// *********************************Protected routes************************
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user', [UserController::class, 'user']);
    Route::post('logout', [UserController::class, 'logout']);
    
    // **********************************Post***********************************************
    Route::get("posts/{id}", [PostController::class, 'userPosts'])->name('postlist');
    Route::get("edit/{id}", [PostController::class, 'edit'])->name('edit');
    Route::put("post/update/{id}", [PostController::class, 'update']);
    Route::delete("post/delete/{id}", [PostController::class, 'destroy']);
    Route::post("post/create", [PostController::class, 'store']);
 

    // Managing comments of users
    Route::get('comments', [CommentController::class, 'index']);
});

  // ******************************Comment routes**************************************
  Route::post('comments', [CommentController::class, 'store']);
  Route::get('comment/{id}', [CommentController::class, 'show']);
  Route::put('comment/{id}', [CommentController::class, 'update']);
  Route::delete("comment/delete{id}", [CommentController::class, 'destroy']);



// Route::middleware('auth:sanctum')->group(function(){
//     Route::get('user',[UserController::class,'user']);
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
