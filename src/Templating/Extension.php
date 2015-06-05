<?php

namespace Ytnuk\Templating;

use Nette;
use Ytnuk;

/**
 * Class Extension
 *
 * @package Ytnuk\Templating
 */
final class Extension extends Nette\DI\CompilerExtension implements Ytnuk\Config\Provider
{

	/**
	 * @var array
	 */
	private $defaults = [
		'templates' => []
	];

	/**
	 * @inheritdoc
	 */
	public function getConfigResources()
	{
		return [
			'services' => [
				[
					'class' => Template\Factory::class,
					'tags' => [Ytnuk\Application\Extension::COMPONENT_TAG]
				]
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->getDefinition($builder->getByType(Template\Factory::class))->setArguments([$config['templates']]);
	}
}
