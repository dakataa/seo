<?php

namespace Dakataa\Seo\Service;

use Dakataa\Seo\Attribute\Breadcrumb\BreadcrumbItem;

class Breadcrumb
{
	/**
	 * @var BreadcrumbItem[]
	 */
	private array $path = [];

	public function add(BreadcrumbItem $breadcrumbItem): static
	{
		$this->path[] = $breadcrumbItem;

		return $this;
	}

	public function getPath(): array
	{
		return $this->path;
	}

	public function clear(): static
	{
		$this->path = [];

		return $this;
	}

	public function exists(?string $route): bool
	{
		if (!empty($route)) {
			foreach ($this->path as $item) {
				if ($item->route == $route) {
					return true;
				}
			}
		}

		return false;
	}
}
