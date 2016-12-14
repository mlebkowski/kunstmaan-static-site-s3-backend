<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\Service;

use Aws\S3\S3Client;

class BucketFactory
{
    /**
     * @var LocationParser
     */
    private $parser;

    /**
     * @var array
     */
    private $bucketConfig;

    /**
     * @param LocationParser $parser
     * @param array $bucketConfig
     */
    public function __construct(LocationParser $parser, array $bucketConfig)
    {
        $this->parser = $parser;
        $this->bucketConfig = $bucketConfig;
    }


    /**
     * @param string $location
     * @return StorageClient
     */
    public function getBucket($location)
    {
        $location = $this->parser->parse($location);

        $defaults = reset($this->bucketConfig);
        if (isset($location['bucket_name']) && isset($this->bucketConfig[$location['bucket_name']])) {
            $defaults = $this->bucketConfig[$location['bucket_name']];
        }

        $location += $defaults;

        return new StorageClient(new S3Client([
            'credentials' => [
                'key' => $location['access_key'],
                'secret' => $location['access_secret'],
            ],
            'region' => $location['region'],
            'version' => '2006-03-01',
        ]), $location['bucket_name'], $location['target_path'], $location['acl']);

    }
}
