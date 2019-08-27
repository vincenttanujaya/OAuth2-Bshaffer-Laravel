<?php
use Illuminate\Support\Facades\App;
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
App::singleton('oauth2', function() {
    $storage = new OAuth2\Storage\Pdo(array(
        'dsn' => 'mysql:dbname=oauth2;host:localhost',
        'username' => 'root',
        'password' => ''
    ));
    $server = new OAuth2\Server($storage);
    
    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
    
    return $server;
});

Route::post('oauth/token', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->handleTokenRequest($bridgeRequest,$bridgeResponse);
    return $bridgeResponse;
});

Route::post('oauth/token-verify', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->verifyResourceRequest($bridgeRequest,$bridgeResponse);
    return response()->json($bridgeResponse);
});

Route::get('/', function () {
    return view('welcome');
});
