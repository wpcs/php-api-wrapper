<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class CreateVersionRequest extends ApiRequest
{
    private $name;
    private $wordpressVersion;
    private $phpVersion;
    private $snapshotId;
    private $snapshotPath;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function setWordPressVersion($wordpressVersion)
    {
        $this->wordpressVersion = $wordpressVersion;
        return $this;
    }
    
    public function setPhpVersion($phpVersion)
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }
    
    public function setSnapshotId($snapshotId)
    {
        $this->snapshotId = $snapshotId;
        return $this;
    }
    
    public function setSnapshotPath($snapshotPath)
    {
        $this->snapshotPath = $snapshotPath;
        return $this;
    }

    public function send()
    {
        if($this->snapshotId && $this->snapshotPath)
        {
            throw new \Exception('Cannot use both a snapshot ID and a local snapshot path to create a version.');
        }

        $client = $this->getClient();

        $body = [
            'name' => $this->name,
            'wordPressVersion' => $this->wordpressVersion,
            'phpVersion' => $this->phpVersion,
        ];

        if($this->snapshotId)
        {
            $body['snapshotId'] = $this->snapshotId;
        }

        if($this->snapshotPath)
        {
            $body['useCustomUpload'] = true;
        }

        $response = $client->request('POST', 'v1/versions', [
            'json' => $body,
        ]);

        if($this->snapshotPath) {
            $responseBody = json_decode($response->getBody());

            $s3Client = new Client([ 'timeout'  => 300 ]);
            $data = Psr7\Utils::tryFopen($this->snapshotPath, 'r');
            $s3Client->request('PUT', $responseBody->uploadUrl, ['body' => $data]);    
        }

        return $response;
    }
}
