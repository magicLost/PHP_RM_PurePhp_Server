<?php

include_once __DIR__.'/../../exception/exception.php';

/* 
* @params $options [ minMessage, maxMessage, max, min ]
*/
function length(string $value, array $options){

    if($value === '') return "";

    $defaultMinMessage = "Минимальное количество символов в строке - ";
    $defaultMaxMessage = "Максимальное количество символов в строке - ";

    if(!is_string($value)){
        throw new ValidatorException("[LENGTH] We need string");
    }

    $minLength = isset($options['min']) ? (int)$options['min'] : 0;
    $maxLength = isset($options['max']) ? (int)$options['max'] : 0;

    if($minLength === 0 && $maxLength === 0){
        throw new ValidatorException("[LENGTH] No options for compare");
    }

    $length = strlen($value);

    if($minLength > 0){

        if($length < $minLength){
            
            if(isset($options['minMessage'])){

                return $options['minMessage'];

            }else{

                return $defaultMinMessage . $minLength;

            }
        }
    }

    if($maxLength > 0){

        if($length > $maxLength){
            
            if(isset($options['maxMessage'])){

                return $options['maxMessage'];

            }else{

                return $defaultMaxMessage . $maxLength;

            }
        }
    }

    return '';
}

/* 
* @params $options [ errorMessage, pattern ]
*/
function regex(string $value, array $options){

    if($value === '') return "";

    if(!is_string($value)){
        throw new ValidatorException("[REGEX] We need string");
    }

    if(!isset($options['pattern'])){
        throw new ValidatorException("[REGEX] We need pattern");
    }

    $defaultErrorMessage = "Строка содержит запрещенный символ.";

    $arr_result = [];

    $status = preg_match($options['pattern'], $value, $arr_result);

    if($status === false){
        throw new ValidatorException("[REGEX] Bad preg_match");
    }

    if($arr_result[0] !== $value){
        if(isset($options['errorMessage'])){
            return $options['errorMessage'];
        }

        return $defaultErrorMessage;
    }
}

/* 
* @params $options [ errorMessage ]
*/
function email(string $value, array $options){
    if($value === '') return "";

    if(!is_string($value)){
        throw new ValidatorException("[EMAIL] We need string");
    }

    $defaultErrorMessage = "Недопустимый формат адреса электронной почты.";

    $pattern = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/';

    $arr_result = [];

    $status = preg_match($pattern, $value, $arr_result);

    if(status === false){
        throw new ValidatorException("[EMAIL] Bad preg_match");
    }

    if($arr_result[0] !== $value){
        if(isset($options['errorMessage'])){
            return $options['errorMessage'];
        }

        return $defaultErrorMessage;
    }
}