<?php

namespace WebEdit\Templating;

use Nette\DI;
use WebEdit\Application;
use WebEdit\Config;

/**
 * Class Extension
 *
 * @package WebEdit\Templating
 */
final class Extension extends DI\CompilerExtension implements Config\Provider
{

	/**
	 * @var array
	 */
	private $defaults = [
		'templates' => []
	];

	/**
	 * @return array
	 */
	public function getConfigResources()
	{
		return [
			'services' => [
				'template' => [
					'class' => Template\Factory::class,
					'tags' => [Application\Extension::COMPONENT_TAG]
				]
			]
		];
	}

	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->getDefinition('template')
			->setArguments([$config['templates']]);
	}
}
