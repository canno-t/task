<?php

namespace App\Responses;

class ResponseBuilder implements AppResponse
{
    protected $status;
    protected $message;

    private array $params;
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
