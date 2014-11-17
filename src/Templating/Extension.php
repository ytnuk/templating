<?php

namespace WebEdit\Templating;

use Nette;
use WebEdit;

/**
 * Class Extension
 *
 * @package WebEdit\Templating
 */
final class Extension extends Nette\DI\CompilerExtension implements WebEdit\Config\Provider
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
					'tags' => [WebEdit\Application\Extension::COMPONENT_TAG]
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
