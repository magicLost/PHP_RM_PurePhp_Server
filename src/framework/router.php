<?php

function router(): string{

    $path = $_SERVER['REQUEST_URI'];

    if($_SERVER['QUERY_STRING']){

        $path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $path);

    }

    //echo "PATH 1 - " . $path;

    $path = '/' . explode("/", $path)[1];

    return $path;

}