<?php

namespace Dakataa\Seo;

use Dakataa\Seo\Service\Breadcrumb;
use Dakataa\Seo\Service\Metadata;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DakataaSeoBundle extends AbstractBundle
{
	public function build(ContainerBuilder $container): void
	{
		$container
			->register(Breadcrumb::class, Breadcrumb::class);

		$container
			->register(Metadata::class, Metadata::class);

	}
}
