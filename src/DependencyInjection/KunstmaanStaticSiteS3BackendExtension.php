<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\DependencyInjection;

use Aws\S3\S3Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KunstmaanStaticSiteS3BackendExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = $this->processConfiguration(new Configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->configureBuckets($configs, $container);
    }

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    private function configureBuckets(array $configs, ContainerBuilder $container)
    {

        $defaultCredentials = ['access_key' => $configs['access_key'], 'access_secret' => $configs['access_secret']];

        $value = ['default' => $defaultCredentials];

        foreach ($configs['buckets'] as $key => $bucket) {
            $value[$key] = $bucket + $defaultCredentials;
        }

        $container->setParameter('kunstmaan_static_site_s3_backend.buckets', $value);
    }
}
