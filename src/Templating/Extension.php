<?php

namespace WebEdit\Templating;

use Latte;
use Nette\Bridges;
use WebEdit\Application;
use WebEdit\Module;
use WebEdit\Templating;

final class Extension extends Module\Extension implements Application\Provider
{

	public function getResources()
	{
		return [
			'filters' => [],
			'templates' => []
		];
	}

	public function getApplicationResources()
	{
		return [
			'presenter' => [
				'components' => [
					'template' => [
						'class' => Templating\Template\Factory::class,
					]
				]
			],
			'services' => [
				Bridges\ApplicationLatte\TemplateFactory::class,
				'nette.latteFactory' => [
					'class' => Latte\Engine::class,
					'implement' => Bridges\ApplicationLatte\ILatteFactory::class,
					'setup' => [
						'setTempDirectory' => [$this->getContainerBuilder()->expand('%tempDir%/cache/latte')],
						'setAutoRefresh' => [$this->getContainerBuilder()->expand('%debugMode%')]
					]
				]
			]
		];
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$builder->getDefinition('template')
			->setArguments([$this['templates']]);
		$latte = $builder->getDefinition('nette.latteFactory');
		foreach ($this['filters'] as $name => $filter) {
			$latte->addSetup('addFilter', [$name, $filter]);
		}
	}

}
