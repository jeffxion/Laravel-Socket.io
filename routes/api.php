<?php

use Illuminate\Http\Request;

use App\Message;

use App\Events\Pasmo;

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

// Return index page with the Vue component we will crete soon
Route::get('/', function () {
    return view('welcome');
});

// Return all messages that will populate our chat messages
Route::get('/getAll', function () {
    $messages = Message::take(200)->pluck('content');
    return $messages;
})->middleware('cors');

// Allows us to post new message
Route::post('/post', function () {
    $message = new Message();
    $content = request('message');
    $message->content = $content;
    $message->save();

    broadcast(new Pasmo($content));
    return $content;
})->middleware('cors');