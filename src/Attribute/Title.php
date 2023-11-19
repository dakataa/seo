<?php

namespace Dakataa\Seo\Attribute;

use Attribute;
use Stringable;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Title implements Stringable
{
    public function __construct(public string $value)
    {
    }

	public function __toString(): string
	{
		return $this->value;
	}
}
