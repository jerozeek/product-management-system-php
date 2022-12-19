<?php
class foo{

    private $bike;

    private function get_bike(){
        echo 'jerry';
    }



}




$foo = new foo();

$method = new \ReflectionMethod(foo::class,'get_bike');
$method->setAccessible(true);
$f = new foo();
$method->invoke($f);