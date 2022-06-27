<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class DeleteVersionRequest extends ApiRequest
{
    private $versionId;

    public function setVersionId($versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    public function send()
    {
        $client = $this->getClient();

        $response = $client->request('DELETE', 'v1/versions', [
            'query' => [
                'versionId' => $this->versionId,
            ],
        ]);

        return $response;
    }
}
