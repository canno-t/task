<?php

namespace App\Responses;

class LoginResponse extends ResponseBuilder
{

    public static $succesResponse = 'user logged in';


    public function __construct( $status, $message)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public static function setResponse($status, $message=null){
        $status = ($status === false) ? 'fail' : 'success';
        $message  = $message?:self::$succesResponse;
        return new self($status, $message);
    }
}
