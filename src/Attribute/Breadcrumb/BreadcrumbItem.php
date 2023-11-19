<?php

namespace Dakataa\Seo\Attribute\Breadcrumb;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class BreadcrumbItem
{
	public function __construct(
		public ?string $title = null,
		public ?string $route = null,
		public ?array $routeParams = null
	) {
	}

}
