<?php
class Config
{
    public static function get($directory = null){
        if ($directory){
            $config = $GLOBALS['config'];
            $directory = explode('/',$directory);
            foreach ($directory as $bit){
                if (isset($config[$bit])){
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }

}