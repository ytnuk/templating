<?php

namespace Kutny\Templating;

use Nette;
use Kutny;

/**
 * Class Extension
 *
 * @package Kutny\Templating
 */
final class Extension extends Nette\DI\CompilerExtension implements Kutny\Config\Provider
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
					'tags' => [Kutny\Application\Extension::COMPONENT_TAG]
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
