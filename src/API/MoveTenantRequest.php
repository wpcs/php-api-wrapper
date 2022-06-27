<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class MoveTenantRequest extends ApiRequest
{
    private $tenantId;
    private $externalId;
    private $targetVersionId;

    /**
     * Sets the tenant ID of the tenant to move.
     * 
     * @since 1.0.0
     *
     * @param string $tenantId
     * @return MoveTenantRequest
     */
    public function setTenantId(string $tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * Sets the external ID of the tenant to move.
     * 
     * @since 1.0.0
     *
     * @param string $externalId
     * @return MoveTenantRequest
     */
    public function setExternalId(string $externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * Sets the ID of the version to move the tenants to.
     * 
     * @since 1.0.0
     *
     * @param string $targetVersionId
     * @return MoveTenantRequest
     */
    public function setTargetVersionId(string $targetVersionId)
    {
        $this->targetVersionId = $targetVersionId;
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

