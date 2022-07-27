<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class GetVersionsRequest extends ApiRequest
{
    private $versionId;
    private $versionName;

    /**
     * Sets the ID of the version to get information on. Without this or setVersionName, this request will return the whole list of version in the product.
     * 
     * @since 1.0.0
     *
     * @param string $versionId
     * @return void
     */
    public function setVersionId(string $versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    /**
     * Sets the name of the version to get information on. Without this or setVersionId, this request will return the whole list of version in the product.
     * 
     * @since 1.0.0
     *
     * @param string $versionName
     * @return void
     */
    public function setVersionName(string $versionName)
    {
        $this->versionName = $versionName;
        return $this;
    }

    /**
     * Sends the request.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    public function send()
    {
        $client = $this->getClient();

        $query = [];

        if($this->versionId)
        {
            $query['versionId'] = $this->versionId;
        }

        if($this->versionName)
        {
            $query['versionName'] = $this->versionName;
        }

        $response = $client->request('GET', 'v1/versions', [
            'query' => $query,
        ]);
        $responseBody = json_decode($response->getBody());

        if($response->getStatusCode() !== 200)
        {
            $message = Helpers::get_error_message($responseBody);
            throw new \Exception($message, $response->getStatusCode());
        }

        return $responseBody;
    }
}

