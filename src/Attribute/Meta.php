<?php

namespace Dakataa\Seo\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Meta
{
	public function __construct(
		public ?string $name = null,
		public ?string $content = null,
		public ?string $property = null,
		public ?string $charset = null,
		?string $httpEquiv = null
	) {
	}

}
