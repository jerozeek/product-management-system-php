<?php


class Redirect
{
    public static function to($link){
        header('location:'.$link);
    }
}