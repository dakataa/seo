# SEO Helper
This library helps your to manipulate page title, meta tags using PHP Attributes.

## Example
How to apply title, meta tags and breadcrumb in Controller.

```php
<?php

namespace App\Controller;

use Dakataa\Seo\Attribute\Breadcrumb\BreadcrumbItem;use Dakataa\Seo\Attribute\Meta;
use Dakataa\Seo\Attribute\Title;
use Dakataa\Seo\Service\Breadcrumb;use Dakataa\Seo\Service\Metadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FrontController extends AbstractController {

	#[Title('Homepage')]
	#[Meta('description', 'Welcome to my SEO Homepage')]
	#[Route]
	public function index() {
	
	}
	
	#[Route('/products')]
	public function products(Metadata $metadata, Breadcrumb $breadcrumb) {
		$metadata
		->setTitle('Second way to set Page Title')
		->setMeta([new Meta('description', 'Product Page')]);
		
		// Add breadcrumb
		$breadcrumb->add(new BreadcrumbItem('Products'));
	}
	
	
	#[Title('Products')]
	#[Meta('description', 'Welcome to my SEO Homepage')]
	#[BreadcrumbItem('Products')]
	#[BreadcrumbItem('{product}')]
	#[Route('/products/view/{id}')]
	#[Entity('product', expr: 'repository.findById(id)', class: Product::class)]
	public function productView(Metadata $metadata, $product) {
		$metadata->setTitle('Second way to set Page Title');
	}
	
}
```

How to use in twig templates.
You have to configure twig to use services.
`config/package/twig.yaml`
```yaml
twig:
  globals:
    breadcrumb: "@Dakataa\\Seo\\Service\\Breadcrumb"
    metadata: "@Dakataa\\Seo\\Service\\Metadata"
```

```html
<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ seo.title }}</title>
    {% for meta in seo.tags %}
        {% if meta.name %}
            <meta name="{{ meta.name }}" content="{{ meta.content|trans }}"/>
        {% endif %}
        {% if meta.property %}
            <meta property="{{ meta.property }}" content="{{ meta.content|trans }}"/>
        {% endif %}
    {% endfor %}
</head>
<body>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        {% for item in breadcrumb.path %}
        {% if item.route %}
        <li class="breadcrumb-item">
            <a href="{{ url(item.route, item.routeParams ?? []) }}">{{ item.title }}</a>
        </li>
        {% else %}
        <li class="breadcrumb-item active" aria-current="page">{{ item.title }}</li>
        {% endif %}
        {% endfor %}
    </ol>
</nav>
</body>
</html>
```
