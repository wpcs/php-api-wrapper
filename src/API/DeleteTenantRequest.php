<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class DeleteTenantRequest extends ApiRequest
{
    private $tenantId;
    private $externalId;

    /**
     * Sets the ID of the tenant to delete.
     * 
     * @since 1.0.0
     *
     * @param string $tenantId
     * @return DeleteTenantRequest
     */
    public function setTenantId(string $tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * Sets the external ID of the tenant to delete.
     * 
     * @since 1.0.0
     *
     * @param string $externalId
     * @return DeleteTenantRequest
     */
    public function setExternalId(string $externalId)
    {
        $this->externalId = $externalId;
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

