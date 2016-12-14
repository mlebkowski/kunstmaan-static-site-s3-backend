<?php

namespace Nassau\KunstmaanStaticSiteS3Backend\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kunstmaan_static_site_s3_backend');

        $rootNode->children()->scalarNode('access_key')->defaultNull();
        $rootNode->children()->scalarNode('access_secret')->defaultNull();

        /** @var ArrayNodeDefinition $buckets */
        $buckets = $rootNode->children()->arrayNode('buckets')
            ->useAttributeAsKey('name')
            ->prototype('array');

        $buckets->children()->scalarNode('name');
        $buckets->children()->scalarNode('bucket_name')->isRequired();
        $buckets->children()->scalarNode('access_key');
        $buckets->children()->scalarNode('access_secret');
        $buckets->children()->scalarNode('region')->defaultValue('eu-west-1');
        $buckets->children()->scalarNode('acl')->defaultValue('public-read');
        $buckets->children()->scalarNode('target_path')->defaultValue('/');

        return $treeBuilder;
    }
}
