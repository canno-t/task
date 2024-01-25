<?php

namespace App\Responses;

class UserResponse extends ResponseBuilder
{
    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public static function setResponse($status, $message){
        $status = ($status === false) ? 'fail' : 'success';
        return new self($message, $status);
    }
}
