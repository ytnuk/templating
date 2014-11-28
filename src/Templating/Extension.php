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
	 * @return array
	 */
	public function getConfigResources()
	{
		return [
			'services' => [
				'template' => [
					'class' => Template\Factory::class,
					'tags' => [Ytnuk\Application\Extension::COMPONENT_TAG]
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
