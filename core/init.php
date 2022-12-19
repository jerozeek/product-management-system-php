<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
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
    )
);

spl_autoload_register(function ($class){
    require_once 'models/'.$class.'.php';
});