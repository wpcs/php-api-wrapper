<?php
namespace WPCS\API\Versions;

use WPCS\API\ApiRequest;

class SetVersionAsProdRequest extends ApiRequest
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

        $response = $client->request('PUT', 'v1/versions/production', [
            'json' => [
                'versionId' => $this->versionId,
            ],
        ]);

        return $response;
    }
}

