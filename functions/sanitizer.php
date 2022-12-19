<?php
function string($string){
    return htmlentities($string, ENT_QUOTES,'UTF-8');
}
function display($string){
    return $string;
}
