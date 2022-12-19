<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host'=>'localhost',
        'username'=>'root',
        'password'=>'root',
        'db'=>'pems'
    ),
    'remember' =>array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token',
        'user_id'=>'id',
        'role'=>'role_name',
        'active_menu'=>'active',
        'currency'=>'naira',
    )
);
require_once 'functions/sanitizer.php';
spl_autoload_register(function ($class){
    require_once 'models/'.$class.'.php';
});
Session::set(Config::get('session/currency'),'₦');



