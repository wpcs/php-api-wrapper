<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class GetVersionsRequest extends ApiRequest
{
    private $versionId;
    private $versionName;

    public function setVersionId($versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    public function setVersionName($versionName)
    {
        $this->versionName = $versionName;
        return $this;
    }
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

        return $response;
    }
}

