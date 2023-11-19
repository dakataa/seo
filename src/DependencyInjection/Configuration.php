<?php

namespace Dakataa\Seo\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

	public function getConfigTreeBuilder(): TreeBuilder
	{
		$treeBuilder = new TreeBuilder('seo');
		$treeBuilder->getRootNode()
			->children()
			->arrayNode('title')
			->children()
			->scalarNode('default')->end()
			->scalarNode('prefix')->end()
			->scalarNode('separator')->defaultValue('/')->end()
			->scalarNode('affix')->end()
			->end()
			->end()
			->end();

		return $treeBuilder;
	}
}
