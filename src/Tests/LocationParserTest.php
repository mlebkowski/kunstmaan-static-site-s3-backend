<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\Tests;

use Nassau\KunstmaanStaticSiteS3Backend\Service\LocationParser;

class LocationParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string      $location
     * @param string|null $bucketName
     * @param string|null $user
     * @param string|null $path
     *
     * @dataProvider data
     */
    public function test($location, $bucketName = null, $user = null, $path = null)
    {
        $location = (new LocationParser())->parse($location);

        if ($bucketName) {
            $this->assertEquals($bucketName, $location['bucket_name']);
        } else {
            $this->assertArrayNotHasKey('bucket_name', $location);
        }

        if ($user) {
            $this->assertEquals($user, $location['access_key']);
        } else {
            $this->assertArrayNotHasKey('access_key', $location);
        }

        if ($path) {
            $this->assertEquals($path, $location['target_path']);
        } else {
            $this->assertArrayNotHasKey('target_path', $location);
        }
    }


    public function data()
    {
        return [
            ['s3://s3.amazonaws.com/bucket', 'bucket'],
            ['s3://s3.amazonaws.com/bucket/', 'bucket', null, "/"],
            ['s3://s3.amazonaws.com/bucket/foobar', 'bucket', null, '/foobar'],
            ['s3://user:pass@s3.amazonaws.com/bucket/foobar', 'bucket', 'user', '/foobar'],
            ['s3://s3-eu-west-1.amazonaws.com/bucket', 'bucket'],
            ['s3://bucket.s3.amazonaws.com/', 'bucket', null, '/'],
            ['s3://bucket.s3-eu-west-1.amazonaws.com/foobar', 'bucket', null, '/foobar'],

            ['s3://bucket/foobar', 'bucket', null, '/foobar'],
            ['s3:///foobar', null, null, '/foobar'],
            ['s3:/', null, null, '/'],
            ['s3:', null, null, null],
        ];
    }

}
