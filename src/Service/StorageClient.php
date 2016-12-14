<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\Service;

use Aws\S3\S3Client;

class StorageClient
{

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var string
     */
    private $targetPath = '/';

    private $bucketName;

    private $acl = 'public-read';

    /**
     * @param S3Client $client
     * @param string $targetPath
     * @param $bucketName
     * @param string $acl
     */
    public function __construct(S3Client $client, $bucketName, $targetPath = '/', $acl = 'public-read')
    {
        $this->client = $client;
        $this->targetPath = trim($targetPath, '/');
        $this->bucketName = $bucketName;
        $this->acl = $acl;
    }


    /**
     * @param string $path
     * @param string $body
     * @param array $headers
     * @return string
     */
    public function putObject($path, $body, array $headers = [])
    {
        $response = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'ACL' => $this->acl,
            'Key' => ltrim($this->targetPath . $path, '/'),
            'Body' => $body,
        ] + $headers);

        return $response->get('ObjectURL');
    }
}
