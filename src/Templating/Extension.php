<?php
namespace Ytnuk\Templating;

use Nette;
use Ytnuk;

final class Extension
	extends Nette\DI\CompilerExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'templates' => [],
	];

	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('control'))->setImplement(Control\Factory::class)->setArguments([$this->config['templates']]);
	}
}
