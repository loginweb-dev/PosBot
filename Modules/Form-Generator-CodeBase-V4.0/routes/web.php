<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['IsInstalled'])->group(function () {
    Auth::routes(['register' => env('ENABLE_REGISTRATION', false)]);

    Route::post('registration', 'RegistrationController@store');
});

Route::middleware(['IsInstalled', 'auth', 'bootstrap', 'setDefaultConfig'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home-template', 'HomeController@getTemplate');
    Route::get('/home-assigned-forms', 'HomeController@getAssignedForms');
    Route::get('/test-smtp', 'HomeController@testSMTP');

    Route::get('generate-form-slug', 'FormController@generateFormSlug');
    Route::get('form-copy/{id}', 'FormController@copyForm');
    Route::get('form-generate-widget/{id}', 'FormController@generateWidget');
    Route::resource('forms', 'FormController')
        ->except(['show']);
    Route::get('/form/{id}/download-code', 'FormController@downloadCode');
    Route::get('/form/{id}/get-collaborate', 'FormController@getCollab');
    Route::post('/form/post-collaborate', 'FormController@postCollab');
    Route::get('/edit/{slug}/data/{id}', 'FormDataController@getEditformData');
    Route::post('/update/{slug}/data/{id}', 'FormDataController@postEditformData');
    Route::get('/form-data/{id}', 'FormDataController@show');
    Route::get('/form-data-view/{id}', 'FormDataController@viewData');
    Route::get('/download/{id}/pdf', 'FormDataController@downloadPdf');
    Route::delete('/form-data-destroy/{id}', 'FormDataController@destroy');
    Route::get('/form-data-report/{id}', 'FormDataController@getReport');
    Route::get('manage-profile', 'ManageProfileController@getProfile');
    Route::put('manage-profile-update/{id}', 'ManageProfileController@postProfile');

    Route::get('user-settings', 'ManageSettingsController@getSettings');
    Route::post('post-user-settings', 'ManageSettingsController@postSettings');

    Route::get('subscriptions', 'SubscriptionsController@index');
    Route::get('subscriptions/{id}', 'SubscriptionsController@show');
    Route::get('all-subscriptions', 'SubscriptionsController@getAllSubscriptions');
    Route::resource('form-data-comment', 'FormDataCommentController')->only(['store', 'destroy']);
});

//Superadmin
Route::namespace('Superadmin')
    ->prefix('superadmin')
    ->middleware(['IsInstalled', 'auth', 'bootstrap', 'setDefaultConfig'])
    ->group(function () {
        Route::resource('packages', 'PackageController');
        Route::resource('superadmin-settings', 'SuperadminSettingsController');
        Route::get('users/{id}/toggle-status', 'ManageUsersController@toggleUserActiveStatus');
        Route::post('users/check-email-exist', 'ManageUsersController@checkIfEmailExist');
        Route::resource('users', 'ManageUsersController');
        Route::get('package-subscription', 'PackageSubscriptionsController@index');
        Route::get('package-subscription/{id}/edit', 'PackageSubscriptionsController@edit');
        Route::put('package-subscription/{id}', 'PackageSubscriptionsController@update');

        Route::get('subscription/{package_id}/register-pay', 'SubscriptionPaymentController@subscriptionPay');
        Route::get('subscription/{package_id}/pay', 'SubscriptionPaymentController@pay');
        Route::any('subscription/{package_id}/confirm', 'SubscriptionPaymentController@confirmPayment');
        Route::post('subscription/{package_id}/offline-payment', 'SubscriptionPaymentController@pay_offline');
        Route::get('subscription/{package_id}/paypal-payment', 'SubscriptionPaymentController@pay_paypal');
        Route::get(
            'subscription/{package_id}/paypal-express-checkout',
            'SubscriptionPaymentController@paypalExpressCheckout'
        );
    });

Route::middleware(['IsInstalled', 'bootstrap'])->group(function () {
    Route::resource('forms', 'FormController')
        ->only(['show']);
    Route::get('form-examples', 'FormController@getFormExamples')->name('form-examples');
    Route::post('/form-data/{id}', 'FormDataController@store');
    Route::post('/file-upload', 'UploadController@upload');
    Route::post('/file-delete', 'UploadController@deleteFile');
    Route::get('/existing-file-display', 'UploadController@getExistingFiles');
});

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::remember('lang.js', 2, function () {
        $lang = config('app.locale');

        $files = glob(resource_path('lang/'.$lang.'/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo 'window.i18n = '.json_encode($strings).';';
    exit();
})->name('assets.lang');

include_once('ir.php');
