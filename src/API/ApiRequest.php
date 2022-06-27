<?php
namespace WPCS\API;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

abstract class ApiRequest 
{
    private $region;
    private $apiKey;
    private $apiSecret;

    abstract protected function send();

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    public function getClient()
    {
        $this->setupAuthentication();

        if(!$this->region)
        {
            throw new \Exception('A region must be set before the client can be built.');
        }

        if(!$this->apiKey)
        {
            throw new \Exception('An API key must be set before the client can be built.');
        }

        if(!$this->apiSecret)
        {
            throw new \Exception('An API secret must be set before the client can be built.');
        }

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            $token = base64_encode("{$this->apiKey}:{$this->apiSecret}");
            return $request->withHeader('Authorization', "Basic $token");
        }));

        return new Client([
            'handler' => $stack,
            'base_uri' => "https://api.{$this->region}.wpcs.io",
            'timeout'  => 20,
        ]);
    }

    private function getAuthValue($value_name)
    {
        if(defined($value_name))
        {
            return constant($value_name);
        }
    }

    private function setupAuthentication()
    {
        if (!$this->region)
        {
            $this->region = $this->getAuthValue('WPCS_API_REGION');
        }

        if (!$this->apiKey)
        {
            $this->apiKey = $this->getAuthValue('WPCS_API_KEY');
        }

        if (!$this->apiSecret)
        {
            $this->apiSecret = $this->getAuthValue('WPCS_API_SECRET');
        }
    }
}
