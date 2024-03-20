<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashBoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('front.home');
Route::get('/jobs',[JobController::class,'jobs'])->name('front.jobs');
Route::get('/job-details/{id}',[JobController::class,'jobDetail'])->name('front.jobDetails');
Route::post("/apply-jobs",[JobController::class,'applyJobs'])->name('front.applyJobs');
Route::post("/save-jobs",[JobController::class,'saveJobs'])->name('front.saveJobs');


Route::group(['prefix'=>'admin','middleware'=>'checkRole'],function(){
    Route::get('/dashboard',[DashBoardController::class,'index'])->name('admin.dashboard');

    // Category Routes

    Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
    Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
});

Route::group(['prefix'=>'account'],function(){

    Route::group(['middleware'=>'guest'],function(){
        Route::get('/register',[AccountController::class,'accountRegister'])->name('front.accountRegister');
        Route::post('/processRegister',[AccountController::class,'processRegister'])->name('front.processRegister');
        Route::get('/login',[AccountController::class,'login'])->name('front.accountLogin');
        Route::post('/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });

    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
        Route::put('/updateProfile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-jobs',[AccountController::class,'createJobs'])->name('account.createJobs');
        Route::post('/save-jobs',[AccountController::class,'saveJobs'])->name('account.saveJobs');
        Route::get('/my-jobs',[AccountController::class,'myJobs'])->name('account.myJobs');
        Route::get('/edit-jobs/{jobId}',[AccountController::class,'editJobs'])->name('account.editJobs');
        Route::put('/update-jobs/{jobId}',[AccountController::class,'updateJobs'])->name('account.updateJobs');
        Route::delete('/delete-jobs/{id}',[AccountController::class,'deleteJobs'])->name("account.deleteJob");
        Route::get('/applied-jobs',[AccountController::class,'appliedJobs'])->name('account.appliedJobs');
        Route::delete('/delete-application-jobs/{id}',[AccountController::class,'deleteApplicationJob'])->name('account.deleteApplicationJob');
        Route::get('/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        Route::post('/delete-job',[AccountController::class,'deleteSavedJob'])->name('account.deleteSavedJob');
        Route::post('/update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');

    });
});
