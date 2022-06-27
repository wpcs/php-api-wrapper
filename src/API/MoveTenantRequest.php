<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class MoveTenantRequest extends ApiRequest
{
    private $tenantId;
    private $externalId;
    private $targetVersionId;

    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function setTargetVersionId($targetVersionId)
    {
        $this->targetVersionId = $targetVersionId;
        return $this;
    }

    public function send()
    {
        $client = $this->getClient();

        $query = [];

        if($this->tenantId)
        {
            $query['tenantId'] = $this->tenantId;
        }

        if($this->externalId)
        {
            $query['externalId'] = $this->externalId;
        }

        if(!$this->tenantId && !$this->externalId)
        {
            throw new \Exception('Must set either a tenant ID or an external ID to move a tenant.');
        }

        if(!$this->targetVersionId)
        {
            throw new \Exception('Must set a target version ID to move the tenant to.');
        }

        $response = $client->request('POST', 'v1/tenants/version', [
            'query' => $query,
            'json' => [
                'targetVersionId' => $this->targetVersionId,
            ],
        ]);

        return json_decode($response->getBody());
    }
}

