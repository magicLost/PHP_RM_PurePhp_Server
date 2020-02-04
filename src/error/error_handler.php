<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function friendlyErrorType($type)
{
    switch($type)
    {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_COMPILE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_COMPILE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
    }
    return $type;
} 

function log_error(string $whoCatch, string $type, string $message, string $file, string $line){

    $time = date("Y-m-d H:i:s"); 

    $typeString = friendlyErrorType($type);

    $content = "WHO CATCH - $whoCatch \n TIME - $time \n TYPE - $typeString \n MESSAGE - $message \n FILE - $file \n LINE - $line \n \t ----------------------------- \n";

    file_put_contents(__DIR__.'/error_log.txt', $content, FILE_APPEND);
}

function check_for_fatal(){

    $error = error_get_last();

    if($error !== null){
        log_error("FATAL ERROR", $error["type"], $error["message"], $error["file"], $error["line"]);
    }

}

function error_handler(int $errno , string $errstr, string $errfile, int $errline){

    log_error("ERROR HANDLER", (string)$errno, $errstr, $errfile, (string)$errline);

    throw new Exception($errstr, $errno);
    
}

function exception_handler(Throwable $exception){

    log_error("EXCEPTION HANDLER", $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());

    
} 


register_shutdown_function("check_for_fatal");
set_error_handler("error_handler");
set_exception_handler("exception_handler");



