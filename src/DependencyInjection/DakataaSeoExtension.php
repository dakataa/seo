<?php

namespace Dakataa\Seo\DependencyInjection;

use Dakataa\Seo\EventSubscriber\SeoEventSubscriber;
use Dakataa\Seo\Service\Breadcrumb;
use Dakataa\Seo\Service\Metadata;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class DakataaSeoExtension extends Extension
{
	public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
	{
		return new Configuration();
	}

	public function load(array $configs, ContainerBuilder $container)
	{
		$container
			->register(Breadcrumb::class, Breadcrumb::class)
			->setAutowired(true);

		$container
			->register(Metadata::class, Metadata::class)
			->setAutowired(true);

		$container
			->register(SeoEventSubscriber::class, SeoEventSubscriber::class)
			->addTag('kernel.event_subscriber')
			->setAutowired(true);
	}
}
