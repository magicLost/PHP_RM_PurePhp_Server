<?php

$config = require_once __DIR__.'/../../../config/smtp_pass.php';

$arr = [ $config['login'] => $config['pass'] ];

foreach($arr as $key => $val){
    echo 'KEY = ' . $key . " | VALUE = " . $val;
}

