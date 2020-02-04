<?php

include_once __DIR__.'/../exception/exception.php';


function feedback(){

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        try{

            header('Content-Type: application/json');

            /* GET DATA */
    
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
            $token = isset($_POST['token']) ? trim($_POST['token']) : '';
            $photoId = isset($_POST['photo_id']) ? trim($_POST['photo_id']) : '';
    
    
            /* VALIDATE DATA */
    
            /* include_once __DIR__.'/../utils/createFeedbackToken.php';
    
            $tokenToCompare = createFeedbackToken($name, $phone, $email);
    
            if($token !== $tokenToCompare){
    
                return json_encode(
                    [
                        'status' => 'fail', 
                        'data' => [
                            'error' => ['Нет ответа...'],
                        ]
                    ]
                );
            } */
    
            include_once __DIR__.'/../utils/validators/validators.php';
    
            if($name === "")
                throw new NotValidException("Представьтесь, пожалуйста.");

            nameValidation($name);
    
            if($email === "" && $phone === "")
                throw new NotValidException("Укажите, пожалуйста номер телефона или адрес электронной почты иначе мы не сможем с вами связаться.");
    
            if($phone !== ""){
                phoneValidation($phone);
            }
    
            if($email !== ""){
                emailValidation($email);
            }
    
            if($photoId !== "")
                $photoId = filterPhotoIdInput($photoId);
    
            if($comment !== "")
                $comment = htmlspecialchars($comment);

            //send mail
            sendSwiftMail($name, $phone, $email, $photoId, $comment);

            return json_encode(
                [
                    'status' => 'success', 
                    'data' => [
                        'name' => $name,
                    ]
                ]
            );

        }catch(NotValidException $exception){

            return json_encode(
                [
                    'status' => 'fail', 
                    'data' => [
                        'error' => $exception->getMessage()
                    ]
                ]
            );

        }catch(ValidatorException $exception){

            log_error(
                '[FEEDBACK CONTROLLER]', 
                $exception->getCode(), 
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );

            return json_encode(
                [
                    'status' => 'fail', 
                    'data' => [
                        'error' => 'Упс! Какая-то ошибка сервера.'
                    ]
                ]
            );
        }

    }else{

        throw new NotFoundException(
            "/feedback path resolve only post request - ".$_SERVER['REQUEST_METHOD']." given."
        );
    }
}

function sendSwiftMail(
    string $name,
    string $phone,
    string $email,
    string $photoId,
    string $comment
){

    $config = require_once __DIR__.'/../../config/smtp_pass.php';

    $transport = (new Swift_SmtpTransport('smtp.mail.ru', 587, 'tls'))
        ->setUsername($config['login'])
        ->setPassword($config['pass'])
        ;

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);


    $message = "Ответить в течение 15 минут.\n\n";

    $message .= 'Имя клиента - ' . $name . "\n\n";

    $message .= 'Дата и время - ' . date("Y-m-d H:i:s") . "\n\n";

    if($phone !== "")
        $message .= 'Номер телефона клиетна - ' . $phone . "\n\n";

    if($email !== "")
        $message .= 'Электронный адрес клиетна - </p>' . $email . "\n\n";

    if($photoId !== "")
        $message .= 'Клиент хочет работу как на фото - </p>' . $photoId . "\n\n";

    if($comment !== "")
        $message .= 'Комментарий клиента: ' . $comment . "\n\n";


    // Create a message
    $message = (new Swift_Message('Заявка от клиента.'))
        ->setFrom([config['login'] => 'Reklam Market'])
        ->setTo('cvet132@yandex.ru')
        ->setBody($message)
        ;

    // Send the message
    $result = $mailer->send($message);
}

function sendMail(){

    $to = "mail@example.com"; 

    $subject = "Заявка от клиетна."; 

    $message = '<h4>Ответить в течение 15 минут.</h4>';

    if($phone !== "")
        $message .= '<p>Номер телефона клиетна - </p>' . $phone;

    if($email !== "")
        $message .= '<p>Электронный адрес клиетна - </p>' . $email;

    if($photoId !== "")
        $message .= '<p>Клиент хочет работу как на фото - </p>' . $photoId;

    if($comment !== "")
        $message .= 'Комментарий клиента: ' . $comment;

    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    $headers .= "From: От кого письмо <reklam-market@example.com>\r\n"; 
    //$headers .= "Reply-To: reply-to@example.com\r\n";  

    mail($to, $subject, $message, $headers); 

}

function nameValidation(string $name): void{

    $nameError = "";

    $nameError = length($name, [
        'max' => 100,
        'min' => 2,
        'minMessage' => 'Имя должно содержать от 2 до 100 символов',
        'maxMessage' => 'Имя должно содержать от 2 до 100 символов'
    ]);

    if($nameError)
        throw new NotValidException($nameError);

    $nameError = regex($name, [
        'pattern' => '/[a-zA-ZА-Яа-я\s]*/u',
        'errorMessage' => 'Недопустимый символ в имени'
    ]);

    if($nameError)
        throw new NotValidException($nameError);

}

function phoneValidation(string $phone): void{

    $phoneError = "";

    $phoneError = length($phone, [
        'max' => 35,
        'min' => 7,
        'minMessage' => 'Минимум семь цифр',
        'maxMessage' => 'Да не может быть...'
    ]);

    if($phoneError)
        throw new NotValidException($phoneError);

    $phoneError = regex($phone, [
        'pattern' => '/[+0-9(][0-9()-]*/',
        'errorMessage' => 'Недопустимый символ в номере телефона'
    ]);

    if($phoneError)
        throw new NotValidException($phoneError);

}

function emailValidation(string $email): void{

    $emailError = "";

    $emailError = length($email, [
        'max' => 255,
        'maxMessage' => 'Длинновато будет.'
    ]);

    if($emailError)
        throw new NotValidException($emailError);

    $emailError = email($email, []);

    if($emailError)
        throw new NotValidException($emailError);

}

function filterPhotoIdInput(string $photoId): string{
    return htmlspecialchars(substr($photoId, 0, 255), ENT_QUOTES);
}
