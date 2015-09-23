<?php
namespace Ytnuk\Templating;

use Nette;
use Ytnuk;

final class Extension
	extends Nette\DI\CompilerExtension
	implements Ytnuk\Config\Provider
{

	/**
	 * @var array
	 */
	private $defaults = [
		'templates' => [],
	];

	public function getConfigResources() : array
	{
		return [
			'services' => [
				Control\Factory::class,
			],
		];
	}

	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->getDefinition($builder->getByType(Control\Factory::class))->setArguments([$config['templates']]);
	}
}
