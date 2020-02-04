<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../src/error/error_handler.php';

require_once __DIR__.'/../src/framework/router.php';

include_once __DIR__.'/../src/exception/exception.php';


$routes = require_once __DIR__.'/../src/routes/routes.php';

ob_start();

try{

    $route = router();

    if(!isset($routes[$route]))
        throw new NotFoundException("Not found route with name - " . $route);

    $content = call_user_func($routes[$route]);

    echo $content;

}catch(NotFoundException $exception){

    http_response_code(404);

    log_error(
        '[INDEX NOT FOUND]', 
        $exception->getCode(), 
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    );

    echo include_once __DIR__.'/../src/pages/error/not_found.html';

}catch(Exception $exception){

    http_response_code(500);

    log_error(
        '[INDEX INTERNAL ERROR]', 
        $exception->getCode(), 
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    );

    echo include_once __DIR__.'/../src/pages/error/internal_error.html';
}

//var_dump(headers_list());

ob_end_flush();


