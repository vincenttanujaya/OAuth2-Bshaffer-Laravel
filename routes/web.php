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
        'dsn' => 'mysql:dbname=oauthlaravel;host:localhost',
        'username' => 'root',
        'password' => ''
    ));
    $server = new OAuth2\Server($storage, array(
        'allow_implicit' => true,
    ));
    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
    $server->addGrantType(new OAuth2\GrantType\JwtBearer($storage,"localhost:8000"));
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    return $server;
});

Route::post('oauth/token', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());    
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->handleTokenRequest($bridgeRequest,$bridgeResponse);
    return $bridgeResponse;
});

Route::get('oauth/authorize', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());    
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->handleAuthorizeRequest($bridgeRequest,$bridgeResponse, true);
    dd($bridgeResponse);
    return $bridgeResponse;
});

Route::get('oauth/registration/{client_id}', function($client_id){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->getClientInfo($bridgeRequest,$bridgeResponse,$client_id);
    return $bridgeResponse;
});

Route::put('oauth/registration/{client_id}', function($client_id){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->setClient($bridgeRequest,$bridgeResponse,$client_id);
    return $bridgeResponse;
});

Route::delete('oauth/registration/{client_id}', function($client_id){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->deleteClient($bridgeRequest,$bridgeResponse,$client_id);
    return $bridgeResponse;
});

Route::post('oauth/registration', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->setClient($bridgeRequest,$bridgeResponse);
    return $bridgeResponse;
});

Route::get('oauth/token-verify', function(){
    $bridgeRequest = OAuth2\HttpFoundationBridge\Request::createFromRequest(Request::instance());
    $bridgeResponse = new OAuth2\HttpFoundationBridge\Response();
    $bridgeResponse = App::make('oauth2')->getAccessTokenData($bridgeRequest,$bridgeResponse);
    return response()->json($bridgeResponse);
});

Route::get('/', function () {
    return view('welcome');
});
