<?php

namespace Dakataa\Seo;

use Dakataa\Seo\Attribute\Breadcrumb\BreadcrumbItem;
use Generator;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;
use Stringable;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class SeoLoader
{
	protected ExpressionLanguage $expressionLanguage;

	public function __construct()
	{
		$this->expressionLanguage = new ExpressionLanguage();
	}

	protected function prepareReflectionClass(object|string $objectOrClass, ?string $methodName = null, array $methodArguments = null): array
	{
		$objectReflectionClass = new ReflectionClass($objectOrClass);
		if($methodName) {
			$methodReflectionClass = $objectReflectionClass->getMethod($methodName);
			$methodArgumentPositionMap = array_map(
				fn(ReflectionParameter $parameterReflection) => $parameterReflection->getName(),
				$methodReflectionClass->getParameters()
			);

			$methodArguments = [...($methodArguments ?: []), $methodArgumentPositionMap];
		}

		return [
			$objectReflectionClass,
			$methodReflectionClass ?? null,
			$methodArguments
		];
	}

	public function loadAttribute(string $attributeClass, object|string $objectOrClass, string $methodName = null, array $methodArguments = []): Generator
	{
		[$objectReflectionClass, $methodReflectionClass] = ($this->prepareReflectionClass($objectOrClass, $methodName, $methodArguments));

		foreach (
			[
				...$objectReflectionClass->getAttributes($attributeClass),
				...$methodReflectionClass->getAttributes($attributeClass),
			] as $reflectionAttribute
		) {
			$attribute = $reflectionAttribute->newInstance();
			foreach (get_object_vars($attribute) as $property => $value) {
				if($attribute->$property === null) {
					continue;
				}

				$attributeTypeProperty = new ReflectionProperty($attribute, $property);
				if($attributeTypeProperty->getType()?->getName() !== 'string')
					continue;

				$attribute->$property = preg_replace_callback(
					'|{(.+?)}|',
					fn($match) => $this->expressionLanguage->evaluate(rtrim(ltrim($match[0], '{'), '}'), $methodArguments),
					$value
				);

			}

			yield $attribute;
		}
	}

	public function loadBreadcrumb(object|string $objectOrClass, string $methodName = null, array $methodArguments = []): Generator
	{
		[$objectReflectionClass, $methodReflectionClass] = ($this->prepareReflectionClass($objectOrClass, $methodName, $methodArguments));

		foreach (
			[
				...$objectReflectionClass->getAttributes(BreadcrumbItem::class),
				...$methodReflectionClass->getAttributes(BreadcrumbItem::class),
			] as $reflectionAttribute
		) {
			/** @var BreadcrumbItem $breadcrumbItem */
			$breadcrumbItem = $reflectionAttribute->newInstance();

			if (!empty($breadcrumbItem->mapTo)) {
				$methodArgumentValue = $methodArguments[$breadcrumbItem->mapTo] ?? null;
				if (is_string($methodArgumentValue) || $methodArgumentValue instanceof Stringable) {
					$breadcrumbItem->title = $methodArgumentValue;
				}
			}

			foreach (($breadcrumbItem->routeParams ?? []) as $key => $routeParam) {
				if (str_starts_with('expr:', $routeParam)) {
					continue;
				}
				$expr = ltrim($routeParam, 'expr:');
				$breadcrumbItem->routeParams[$key] = $this->expressionLanguage->evaluate($expr, $methodArguments);
			}

			yield $breadcrumbItem;
		}
	}
}
