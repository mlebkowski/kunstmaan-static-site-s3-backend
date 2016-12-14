<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\Service;

use Nassau\KunstmaanStaticSiteBundle\Service\Dumper\StaticItem;
use Nassau\KunstmaanStaticSiteBundle\Service\Dumper\StaticSiteDumper;
use Nassau\KunstmaanStaticSiteBundle\Service\Dumper\Storage;

class S3Backend implements Storage
{

    /**
     * @var BucketFactory
     */
    private $bucketSelector;

    /**
     * @param BucketFactory $bucketSelector
     */
    public function __construct(BucketFactory $bucketSelector)
    {
        $this->bucketSelector = $bucketSelector;
    }


    /**
     * @param string $location
     * @param StaticSiteDumper $dumper
     * @return \Generator|StaticItem[]
     */
    public function storeStaticSite($location, StaticSiteDumper $dumper)
    {
        $bucket = $this->bucketSelector->getBucket($location);

        foreach ($dumper->getStaticSite() as $path => $response) {

            $headers = [
                'ContentType' => $response->headers->get('content-type'),
                'CacheControl' => $response->headers->get('cache-control', 'public, max-age=283824000'),
                'Expires' => $response->headers->get('expires', gmdate('D, d M Y H:i:s T', strtotime('+9 years'))),
                'Content-Disposition' => $response->headers->get('content-disposition', 'inline'),
            ];

            $content = $response->getContent();
            if ($response->isRedirection()) {
                $location = $response->headers->get('location');
                $headers['x-amz-website​-redirect-location'] = $location;
                $headers['content-type'] = 'text/html';
                $content = sprintf('<meta http-equiv="refresh" content="5; url=%s">', $location);
            }

            $s3Url = $bucket->putObject($path, $content, $headers);

            yield new StaticItem($response, $path, $s3Url);
        }
    }
}
