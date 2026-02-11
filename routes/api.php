<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JobEmployeeController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\EarningController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PortfolioItemController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TestimonialController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/public/capture-lead', [LeadController::class, 'publicCapture']);
Route::get('/public/landing-page', [SettingController::class, 'getLandingPageData']);
Route::post('/leads', [LeadController::class, 'store']); // public (landing page)
Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download']);


Route::get('/settings', [SettingController::class, 'index']);
Route::get('/portfolio', [PortfolioItemController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);

Route::get('/public/testimonials', [TestimonialController::class, 'getApproved']); 
Route::post('/submit-story', [TestimonialController::class, 'submitStory']);

Route::middleware('auth:sanctum')->group(function () {
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']); // Add this
Route::delete('/users/{user}', [UserController::class, 'destroy']);

Route::get('/dashboard/operations', [DashboardController::class, 'index']);

Route::get('/analytics/stats', [AnalyticsController::class, 'dashboard']);
Route::get('/analytics/employee/{user}', [AnalyticsController::class, 'employeePerformance']);


    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::patch('/leads/{lead}', [LeadController::class, 'update']);

Route::get('/offers', [OfferController::class, 'allOffers']); // Get everything
Route::get('/offers/{offer}', [OfferController::class, 'show']);
// Add this line near your other offer routes
Route::post('/offers', [OfferController::class, 'store']);
Route::post('/offers/{offer}/convert', [OfferController::class, 'convertToJob']);
Route::get('/leads/{lead}/offers', [OfferController::class, 'index']);
Route::patch('/offers/{offer}', [OfferController::class, 'update']);
Route::post('/offers/{offer}/send-email', [OfferController::class, 'sendEmail']);
Route::get('offers/{offer}/download-pdf', [OfferController::class, 'downloadPdf']);

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::post('/jobs', [JobController::class, 'store']);
Route::put('/jobs/{job}', [JobController::class, 'update']);
Route::post('/jobs/{job}/photos', [JobController::class, 'uploadPhotos']);
Route::delete('/jobs/{job}', [JobController::class, 'destroy']);

Route::post('/jobs/{job}/assign-employees', [JobEmployeeController::class, 'assign']);
// routes/api.php
Route::post('/jobs/{job}/assign', [JobController::class, 'assignCrew']);

Route::get('/earnings', [EarningController::class, 'index']);
Route::get('/me/earnings', [EarningController::class, 'myEarnings']);
Route::post('/earnings', [EarningController::class, 'store']);

Route::get('/invoices', [InvoiceController::class, 'index']);
Route::post('/jobs/{job}/invoice', [InvoiceController::class, 'store']);
Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);


Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{client}', [ClientController::class, 'show']);

// Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);

Route::post('/admins', [AdminUserController::class, 'store']);

Route::post('/expenses', [ExpenseController::class, 'store']);
Route::get('/expenses', [ExpenseController::class, 'index']); 
Route::get('/jobs/{job}/expenses', [ExpenseController::class, 'getByJob']);
Route::post('/jobs/{job}/expenses', [ExpenseController::class, 'store']);

Route::post('/settings', [SettingController::class, 'store']);
Route::post('/settings', [SettingController::class, 'update']);

// Services Management
Route::post('services', [ServiceController::class,'store']);
Route::match(['post','put'],'services/{id}', [ServiceController::class,'update']);
Route::delete('services/{service}', [ServiceController::class,'destroy']);

    
// Create new
// Change the update route to this:
Route::post('portfolio', [PortfolioItemController::class, 'store']);
Route::match(['post', 'put'], 'portfolio/{id}', [PortfolioItemController::class, 'update']);    
// Update existing (We use /id so Laravel knows which one)

// Delete (We use /id so Laravel knows which one)
Route::delete('portfolio/{id}', [PortfolioItemController::class, 'destroy']);


Route::get('/testimonials', [TestimonialController::class, 'index']); // Changed from /pending to match React
Route::put('/testimonials/{id}/approve', [TestimonialController::class, 'approve']);
Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);
Route::post('/leads/{lead}/request-story', [TestimonialController::class, 'sendRequest']);

});
