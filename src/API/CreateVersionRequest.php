<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class CreateVersionRequest extends ApiRequest
{
    private $name;
    private $wordpressVersion;
    private $phpVersion;
    private $snapshotId;
    private $snapshotPath;

    /**
     * Sets the name of the version in WPCS.
     * 
     * @since 1.0.0
     *
     * @param string $name
     * @return CreateVersionRequest
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Sets the version of WordPress the WPCS version will use.
     * 
     * @since 1.0.0
     *
     * @param string $wordpressVersion
     * @return CreateVersionRequest
     */
    public function setWordPressVersion(string $wordpressVersion)
    {
        $this->wordpressVersion = $wordpressVersion;
        return $this;
    }
    
    /**
     * Sets the version of PHP that the WPCS version will run on.
     * 
     * @since 1.0.0
     *
     * @param string $phpVersion
     * @return CreateVersionRequest
     */
    public function setPhpVersion(string $phpVersion)
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }
    
    /**
     * Sets the snapshot ID of the snapshot that the version should be based on.
     * 
     * @since 1.0.0
     *
     * @param string $snapshotId
     * @return CreateVersionRequest
     */
    public function setSnapshotId(string $snapshotId)
    {
        $this->snapshotId = $snapshotId;
        return $this;
    }
    
    /**
     * Sets a local snapshot path that is used to create the version with. The local snapshot will be uploaded to WPCS.
     * 
     * @since 1.0.0
     *
     * @param string $snapshotPath
     * @return CreateVersionRequest
     */
    public function setSnapshotPath(string $snapshotPath)
    {
        $this->snapshotPath = $snapshotPath;
        return $this;
    }

    /**
     * Sends the request.
     * 
     * @since 1.0.0
     *
     * @return object
     */
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
            $extension = pathinfo($this->snapshotPath, PATHINFO_EXTENSION);
            if($extension === "zip")
            {
                $body['asZip'] = true;
            }
        }

        $response = $client->request('POST', 'v1/versions', [
            'json' => $body,
        ]);
        $responseBody = json_decode($response->getBody());

        if($response->getStatusCode() !== 200)
        {
            $message = Helpers::get_error_message($responseBody);
            throw new \Exception($message, $response->getStatusCode());
        }

        if($this->snapshotPath)
        {
            $s3Client = new HttpClient();
            $s3Client->upload_file($responseBody->uploadUrl, $this->snapshotPath);
        }

        return $responseBody;
    }
}
