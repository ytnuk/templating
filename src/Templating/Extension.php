<?php

namespace WebEdit\Templating;

use Nette\DI;
use WebEdit\Application;
use WebEdit\Config;
use WebEdit\Templating;

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
					'class' => Templating\Template\Factory::class,
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
