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

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setVersionId($versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    public function setSnapshotId($snapshotId)
    {
        $this->snapshotId = $snapshotId;
        return $this;
    }

    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function setCustomDomainName($customDomainName)
    {
        $this->customDomainName = $customDomainName;
        return $this;
    }

    public function setTenantName($tenantName)
    {
        $this->tenantName = $tenantName;
        return $this;
    }

    public function setTenantUserRole($tenantUserRole)
    {
        $this->tenantUserRole = $tenantUserRole;
        return $this;
    }

    public function setTenantEmail($tenantEmail)
    {
        $this->tenantEmail = $tenantEmail;
        return $this;
    }

    public function setTenantPassword($tenantPassword)
    {
        $this->tenantPassword = $tenantPassword;
        return $this;
    }

    public function setSnapshotPath($snapshotPath)
    {
        $this->snapshotPath = $snapshotPath;
        return $this;
    }

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
