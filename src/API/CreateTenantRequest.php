<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class CreateTenantRequest extends ApiRequest
{
    private $name; // required
    private $versionId;
    private $snapshotId;
    private $groupName;
    private $externalId;
    private $customDomainName;
    private $tenantName;
    private $tenantUserRole;
    private $tenantEmail;
    private $tenantPassword;
    private $snapshotPath;

    /**
     * Sets the name the tenant will get in WPCS.
     * 
     * @since 1.0.0
     *  
     * @param string $name The tenant name
     *
     * @return CreateTenantRequest
    */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the version the tenant will be created under.
     * 
     * @since 1.0.0
     * 
     * @param string $versionId The version ID
     * 
     * @return CreateTenantRequest
     */
    public function setVersionId(string $versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    /**
     * Sets the snapshot ID of the snapshot that the tenant should be based on.
     * 
     * @since 1.0.0
     *
     * @param string $snapshotId
     * @return CreateTenantRequest
     */
    public function setSnapshotId(string $snapshotId)
    {
        $this->snapshotId = $snapshotId;
        return $this;
    }

    /**
     * Sets the external ID of the tenant. This ID is used for future API requests to identify the tenant with the caller's own IDs.
     * 
     * @since 1.0.0
     *
     * @param string $externalId
     * @return CreateTenantRequest
     */
    public function setExternalId(string $externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * Sets the custom domain name the tenant will be created with. Without this, the tenant will be created with only a WPCS-generated domain name.
     * 
     * @since 1.0.0
     *
     * @param string $customDomainName
     * @return CreateTenantRequest
     */
    public function setCustomDomainName(string $customDomainName)
    {
        $this->customDomainName = $customDomainName;
        return $this;
    }

    /**
     * Sets the username of WordPress user that is created after the tenant is created.
     * 
     * @since 1.0.0
     *
     * @param string $tenantName
     * @return CreateTenantRequest
     */
    public function setTenantName(string $tenantName)
    {
        $this->tenantName = $tenantName;
        return $this;
    }

    /**
     * Sets the user role of the WordPress user that is created on tenant creation.
     * 
     * @since 1.0.0
     *
     * @param string $tenantUserRole
     * @return CreateTenantRequest
     */
    public function setTenantUserRole(string $tenantUserRole)
    {
        $this->tenantUserRole = $tenantUserRole;
        return $this;
    }

    /**
     * Sets the email address of the WordPress user that is created on tenant creation.
     * 
     * @since 1.0.0
     *
     * @param string $tenantEmail
     * @return CreateTenantRequest
     */
    public function setTenantEmail(string $tenantEmail)
    {
        $this->tenantEmail = $tenantEmail;
        return $this;
    }

    /**
     * Sets the password of the WordPress user that is created on tenant creation.
     * 
     * @since 1.0.0
     *
     * @param string $tenantPassword
     * @return CreateTenantRequest
     */
    public function setTenantPassword(string $tenantPassword)
    {
        $this->tenantPassword = $tenantPassword;
        return $this;
    }

    /**
     * Sets a local snapshot path that is used to create the tenant with. The local snapshot will be uploaded to WPCS.
     * 
     * @since 1.0.0
     *
     * @param string $snapshotPath
     * @return CreateTenantRequest
     */
    public function setSnapshotPath(string $snapshotPath)
    {
        $this->snapshotPath = $snapshotPath;
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
        if(!$this->name)
        {
            throw new \Exception('Name is a required field to set.');
        }

        $client = $this->getClient();

        $body = [
            'name' => $this->name,
        ];

        if($this->versionId)
        {
            $body['versionId'] = $this->versionId;
        }

        if($this->snapshotId)
        {
            $body['snapshotId'] = $this->snapshotId;
        }

        if($this->groupName)
        {
            $body['groupName'] = $this->groupName;
        }

        if($this->externalId)
        {
            $body['externalId'] = $this->externalId;
        }

        if($this->customDomainName)
        {
            $body['customDomainName'] = $this->customDomainName;
        }

        if($this->tenantName)
        {
            $body['tenantName'] = $this->tenantName;
        }

        if($this->tenantUserRole)
        {
            $body['tenantUserRole'] = $this->tenantUserRole;
        }

        if($this->tenantEmail)
        {
            $body['tenantEmail'] = $this->tenantEmail;
        }

        if($this->tenantPassword)
        {
            $body['tenantPassword'] = $this->tenantPassword;
        }

        if($this->snapshotPath)
        {
            $body['uploadCustomSnapshot'] = true;
        }

        $response = $client->request('POST', 'v1/tenants', [
            'json' => $body,
        ]);
        $responseBody = json_decode($response->getBody());

        if($this->snapshotPath) {
            $s3Client = new Client([ 'timeout'  => 300 ]);
            $data = Psr7\Utils::tryFopen($this->snapshotPath, 'r');
            $s3Client->request('PUT', $responseBody->uploadUrl, ['body' => $data]);    
        }

        return $responseBody;
    }
}
