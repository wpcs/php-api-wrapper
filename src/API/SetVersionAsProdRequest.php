<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class SetVersionAsProdRequest extends ApiRequest
{
    private $versionId;

    /**
     * Sets the version ID of the version that should become the 'production' version.
     * 
     * @since 1.0.0
     *
     * @param string $versionId
     * @return SetVersionAsProdRequest
     */
    public function setVersionId(string $versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    /**
     * Sends the request.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function send()
    {
        $client = $this->getClient();

        $response = $client->request('PUT', 'v1/versions/production', [
            'json' => [
                'versionId' => $this->versionId,
            ],
        ]);

        return json_decode($response->getBody());
    }
}

