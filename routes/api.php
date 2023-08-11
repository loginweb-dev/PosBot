<?php

use Illuminate\Http\Request;
use App\Events\NewMessage;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('business')->group(function () {
    Route::get('/id/{id}', function ($id) {
        $bussine = App\Business::where('id', $id)->with('locations')->first();
        return $bussine;
    });
});


//rutas de productos
Route::prefix('productos')->group(function () {
    Route::get('/id/{id}', function ($id) {
        $producto = App\Product::find($id);
        return $producto;
    });
    Route::get('/busine/{id}', function ($id) {
        $productos = App\Product::where("business_id", $id)->get();
        return $productos;
    });
});

//rutas de productos
Route::prefix('extras')->group(function () {
    Route::get('/get/{id}', function ($id) {
        $extras = App\Product::where('business_id', $id)->where('category_id', 3)->get();
        return $extras;
    });

});

//rutas de chatbots
Route::prefix('chatbots')->group(function () {
    Route::post('/save', function (Request $request) {
        // return $request;
        App\Chatbots::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'type' => $request->type,
            'busine_id' => $request->busine_id,
        ]);
        return App\Chatbots::where("phone", $request->phone)->get();
    });

    Route::post('/send', function (Request $request) {
        event(new NewMessage($request->message, $request->phone, $request->type, $request->busine));
    });


});

//leads --------------------------------------------
Route::post('leads', function (Request $request) {
    $midata = App\Lead::where("phone", $request->phone)->first();
    if (!$midata) {
        App\Lead::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'categoria' => 'General',
            'session' => $request->session
        ]);
    }else{
        $midata->message = $request->message;
        $midata->save();
    }
    return $request;
});

Route::post('setting', function (Request $request) {
    $minegocio = App\User::where("username", $request->username)->with("business")->first(); 
    $milocation = App\BusinessLocation::where("business_id", $minegocio->business->id)->get();
    $midata = [
        'minegocio' => $minegocio,
        'milocation' => $milocation
    ];
    return $midata;
});

