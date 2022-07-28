<?php
namespace WPCS\API;

class HttpResponse
{
    private $body;
    private $code;

    public function __construct($code, $body)
    {
        $this->body = $body;
        $this->code = $code;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getBody()
    {
        return $this->body;
    }
}
