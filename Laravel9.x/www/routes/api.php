<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

/**
 * 正常なパーソナルアクセストークンが設定されているリクエストの場合のみ、ユーザ情報をレスポンスとして返却する
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * パーソナルアクセストークンを発行し、そのトークンを json response にて返却する
 * 発行に成功すると同時に、oauth_access_tokens テーブルに発行したトークンの情報が保存される
 * User.php に HasApiTokens Trait を継承させたことにより、 createToken 関数が使用可能になっている
 */
Route::get('/publish-token/{id}', fn(string $id)
    => response()->json(
        [ 'token' => User::find($id)->createToken('Test Token')->accessToken ],
    )
);

/**
 * 認証エラー時のレスポンスをここで定義する
 * このルーティングが必要なのは、認証エラー時に Laravel の内部仕様により
 * route alias = login で定義したレスポンスが返却されるためである
 */
Route::get('error', fn() => response()->json(['status' => 'error']) )->name('login');
