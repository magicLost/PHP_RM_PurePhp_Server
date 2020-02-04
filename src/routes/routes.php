<?php

include_once __DIR__.'/../controller/feedback.php';

/* $sapitype = php_sapi_name(); 
if (substr($sapitype, 0, 3) == 'cgi') 
    header("Status: 404 Not Found"); 
else
    header("HTTP/1.1 404 Not Found"); 
 */

return [
    '/' => function(){
        $content = require_once __DIR__.'/../pages/homepage.html';
        return $content;
    },

    '/feedback' =>  'feedback'
];