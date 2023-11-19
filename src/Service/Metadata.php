<?php

namespace Dakataa\Seo\Service;

use Dakataa\Seo\Attribute\Meta;
use Dakataa\Seo\Attribute\Title;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Metadata
{
	const DEFAULT_OPTIONS = [
		'title' => [
			'default' => null,
			'prefix' => null,
			'separator' => '/',
			'affix' => null,
		],
	];

	protected array $options;

	protected ?array $hrefLang = null;

	public function __construct(private array $title = [], private array $meta = [], array $options = [])
	{
		$resolver = new OptionsResolver();
		$this->configureOptions($resolver);

		$this->options = $resolver->resolve($options);
	}

	protected function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(self::DEFAULT_OPTIONS);
	}

	public function setTitle(string $title): static
	{
		$this->title = [new Title($title)];

		return $this;
	}

	public function addTitle(string $title): static {
		$this->title[] = new Title($title);

		return $this;
	}

    public function setMeta(array $meta): static
    {
	    $this->meta = $meta;

	    return $this;
    }

	public function addMeta(Meta $meta): static
	{
		$this->meta[] = $meta;

		return $this;
	}

    public function getTitle(): string
    {
	    return implode(' ', array_filter([
		    $this->options['prefix'],
			implode($this->options['title']['separator'], $this->title) ?: $this->options['default'] ?: '',
		    $this->options['affix'],
	    ]));
    }

    public function getMeta(): ?array
    {
	    return $this->meta;
    }


    public function setHrefLang($route = null, $parameters = array(), $regions = false): self
    {
	    //if regions is true use all active regions
	    $this->hrefLang = ['route' => $route, 'params' => $parameters, 'regions' => $regions];

	    return $this;
    }

    public function getHrefLang(): ?array
    {
	    return $this->hrefLang;
    }
}
