<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class GetTenantsRequest extends ApiRequest
{
    private $tenantId;
    private $externalId;

    /**
     * Sets the ID of the tenant to get information on. Without this or setExternalID, this request will return the whole list of tenants.
     * 
     * @since 1.0.0
     *
     * @param string $tenantId
     * @return void
     */
    public function setTenantId(string $tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * Sets the external ID of the tenant to get information on. Without this or setTenantId, this request will return the whole list of tenants.
     * 
     * @since 1.0.0
     *
     * @param string $externalId
     * @return void
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
     * @return array
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

        $response = $client->request('GET', 'v1/tenants', [
            'query' => $query,
        ]);
        $responseBody = json_decode($response->getBody());

        if($response->getStatusCode() !== 200)
        {
            throw new \Exception($responseBody->message, $responseBody->statusCode);
        }

        return $responseBody;
    }
}
