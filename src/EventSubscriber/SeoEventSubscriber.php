<?php

namespace Dakataa\Seo\EventSubscriber;

use Dakataa\Seo\Attribute\Meta;
use Dakataa\Seo\Attribute\Title;
use Dakataa\Seo\SeoLoader;
use Dakataa\Seo\Service\Breadcrumb;
use Dakataa\Seo\Service\Metadata;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;


class BreadcrumbEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
	    protected SeoLoader $loader,
        protected Breadcrumb $breadcrumb,
        protected Metadata $metadata
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelController',
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelController(ControllerArgumentsEvent $event): void
    {
        if (HttpKernelInterface::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        [$controllerObject, $methodName] = $event->getController();

		foreach ($this->loader->loadBreadcrumb($controllerObject, $methodName, $event->getArguments()) as $breadcrumbItem) {
			$this->breadcrumb->add($breadcrumbItem);
		}

	    foreach ($this->loader->loadAttribute(Title::class, $controllerObject, $methodName, $event->getArguments()) as $title) {
		    $this->metadata->addTitle($title);
	    }

	    foreach ($this->loader->loadAttribute(Meta::class, $controllerObject, $methodName, $event->getArguments()) as $meta) {
		    $this->metadata->addMeta($meta);
	    }

    }
}
