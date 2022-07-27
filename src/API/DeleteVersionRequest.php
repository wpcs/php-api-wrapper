<?php
namespace WPCS\API;

use WPCS\API\ApiRequest;

class DeleteVersionRequest extends ApiRequest
{
    private $versionId;

    /**
     * Sets the ID of the version to delete.
     * 
     * @since 1.0.0
     *
     * @param string $versionId
     * @return DeleteVersionRequest
     */
    public function setVersionId(string $versionId)
    {
        $this->versionId = $versionId;
        return $this;
    }

    /**
     * Sends the request.
     * 
     * @since 1.0.0
     *
     * @return object
     */
    public function send()
    {
        $client = $this->getClient();

        $response = $client->request('DELETE', 'v1/versions', [
            'query' => [
                'versionId' => $this->versionId,
            ],
        ]);

        $responseBody = json_decode($response->getBody());
        if($response->getStatusCode() !== 200)
        {
            $message = Helpers::get_error_message($responseBody);
            throw new \Exception($message, $response->getStatusCode());
        }

        return $responseBody;
    }
}
