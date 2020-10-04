<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', function (){
    return new \App\Http\Resources\UserCollection(\App\User::all());
});

Route::get('/users/{user}', function ($user){
    return new \App\Http\Resources\User(\App\User::find($user));
});

Route::post('/users', function (\Illuminate\Http\Request $request){
    $validate = $request->validate([
        'username' => 'required|min:3|max:16',
        'email' => 'required|email',
        'password' => 'min:6|max:50',
    ]);

    $username = $request->get('username');
    $email = $request->get('email');
    $password = $request->get('password');

    $user = new \App\User();
    $user->name = $username;
    $user->email = $email;
    $user->email_verified_at = now();
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->remember_token = \Illuminate\Support\Str::random(10);
    $user->save();

    return new \App\Http\Resources\User($user);
});

Route::put('/users/{user}', function (\App\User $user, \Illuminate\Http\Request $request){
    $validate = $request->validate([
        'username' => 'required|min:3|max:16',
        'email' => 'required|email',
        'password' => 'min:6|max:50',
    ]);

    $username = $request->get('username');
    $email = $request->get('email');
    $password = $request->get('password');

    $user->name = $username;
    $user->email = $email;
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->save();

    return new \App\Http\Resources\User($user);
});

Route::patch('/users/{user}', function (\App\User $user, \Illuminate\Http\Request $request){
    $validate = $request->validate([
        'password' => 'min:6|max:50',
    ]);

    $password = $request->get('password');

    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->save();

    return response([$user->name => "Password changed"]);
});

Route::delete('/users/{user}', function (\App\User $user){
    $user->delete();

    return response([$user->name => 'deleted']);
});
