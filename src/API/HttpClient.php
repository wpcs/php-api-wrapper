<?php
namespace WPCS\API;

class HttpClient
{
    private $base_uri;
    private $timeout = 10;
    private $ch;
    private $headers = [];

    private $progress_callback;

    public function __construct($opts = [])
    {
        $this->base_uri = $opts['base_uri'] ?? null;
        $this->timeout = $opts['timeout'] ?? 10;
    }

    private function get_curl()
    {
        if(!$this->ch)
        {
            $this->ch = curl_init();
            if ($this->ch === false) {
                throw new \Exception('failed to initialize cURL');
            }

            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        }

        return $this->ch;
    }

    public function add_header($header, $value)
    {
        $this->headers[] = "$header: $value";

        return $this;
    }

    public function set_basic_auth($username, $password)
    {
        $token = base64_encode("{$username}:{$password}");
        $this->add_header('Authorization', "Basic $token");

        return $this;
    }

    public function set_progress_callback($callback)
    {
        $this->progress_callback = $callback;
        return $this;
    }

    public function request($method, $uri, $opts)
    {
        $ch = $this->get_curl();

        if($this->base_uri) {
            $url = $this->base_uri . '/' . $uri;
        } else {
            $url = $uri;
        }

        if(array_key_exists('query', $opts))
        {
            $params = http_build_query($opts['query']);
            $url .= "?$params";
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        if($method === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } else if($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        if(array_key_exists('json', $opts))
        {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($opts['json']) );
            $this->add_header('Content-Type', 'application/json');
        }

        if(array_key_exists('body', $opts))
        {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $opts['body'] );
        }

        return $this->execute_request();
    }

    private function execute_request()
    {
        $ch = $this->get_curl();

        // Globally set options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $output = curl_exec($ch);

        if ($output === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $response = new HttpResponse($code, $output);

        curl_close($ch);
        return $response;
    }

    public function upload_file($url, $filepath)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, fopen($filepath, 'r'));
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filepath));

        if(!empty($this->progress_callback))
        {
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);

            $total = 0;
            $now = 0;

            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($resource, $dltotal, $dlnow, $ultotal, $ulnow) use (&$total, &$now) {
                if($total !== $ultotal || $now !== $ulnow)
                {
                    $total = $ultotal;
                    $now = $ulnow;

                    call_user_func_array($this->progress_callback, [ $total, $now ]);
                }
            });
        }

        curl_exec($ch);
    }
}