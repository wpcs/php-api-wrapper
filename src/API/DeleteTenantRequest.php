<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class DeleteTenantRequest extends ApiRequest
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

        if(!$this->tenantId && !$this->externalId)
        {
            throw new \Exception('Must set either a tenant ID or an external ID to delete a tenant.');
        }

        $response = $client->request('DELETE', 'v1/tenants', [
            'query' => $query,
        ]);

        return json_decode($response->getBody());
    }
}

