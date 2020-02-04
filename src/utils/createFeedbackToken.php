<?php

function createFeedbackToken(string $name, string $phone, string $email){

    $stringToHash = substr(urlencode($name . $phone . $email), 0, 64);

    $token = base64_encode($stringToHash);

    if(strlen($token) > 64){
        $token = substr($token, 0, 64);
    }

    return $token;
}