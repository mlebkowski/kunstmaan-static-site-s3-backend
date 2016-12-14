<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\Service;

class LocationParser
{
    public function parse($location)
    {
        $params = [];

        if (0 === strpos(strtolower($location), 's3')) {
            // parse empty host, i.e. "file:///path" is valid, while "s3:///path" is not
            $location = 'file' . substr($location, 2);
        }

        $location = parse_url($location) + ['host' => "", 'path' => ""];

        if (isset($location['user'])) {
            $params['access_key'] = $location['user'];
        }

        if (isset($location['pass'])) {
            $params['access_secret'] = $location['pass'];
        }

        $re = '/^(?:(?<bucket>[\d\w-]+)\.)? s3 (?: -(?<region>[\w\d-]+))? \.amazonaws.com$/xi';

        $targetPath = $location['path'];

        if (preg_match($re, $location['host'], $matches)) {
            $matches += ['bucket' => '', 'region' => ''];
            $bucketName = $matches['bucket'];

            if (!$bucketName) {
                $bucketName = strtok($location['path'], '/');
                $targetPath = substr($location['path'], strlen('/' . $bucketName));
            }

            $params += array_filter([
                'bucket_name' => $bucketName,
                'region' => $matches['region'],
            ]);

        } elseif ($location['host']) {
            $params['bucket_name'] = $location['host'];
        }

        if ($targetPath) {
            $params['target_path'] = $targetPath;
        }

        return $params;
    }
}
