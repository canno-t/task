<?php

namespace App\Responses;

class ResponseBuilder implements AppResponse
{
    protected $status;
    protected $message;

    private array $params;

    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public static function setResponse($status, $message){
        $status = ($status === false) ? 'fail' : 'success';
        $message  = $message?:static::$succesResponse;
        return new self($status, $message);
    }
    public function returnResponse()
    {
        return array_merge([
            'status'=>$this->status,
            'message'=>$this->message
        ],
        $this->params??[]);
    }

    public function addParam(string $param, $value){
        $this->params[$param] = $value;
        return $this;
    }
}
