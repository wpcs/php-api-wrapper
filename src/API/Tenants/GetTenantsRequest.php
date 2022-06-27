<?php
namespace WPCS\API\Tenants;

use WPCS\API\ApiRequest;

class GetTenantsRequest extends ApiRequest
{
    private $tenantId;
    private $externalId;

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

        $response = $client->request('GET', 'v1/tenants', [
            'query' => $query,
        ]);

        return $response;
    }
}
