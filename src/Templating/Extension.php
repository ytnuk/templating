<?php

namespace WebEdit\Templating;

use WebEdit\Application;
use WebEdit\Module;
use WebEdit\Templating;

final class Extension extends Module\Extension implements Application\Provider
{

    public function getTemplatingResources()
    {
        return [
            'filters' => [],
            'templates' => [
                $this->getContainerBuilder()->expand('%appDir%/templates')
            ]
        ];
    }

    public function getApplicationResources()
    {
        return [
            'presenter' => [
                'components' => [
                    'template' => [
                        'class' => Templating\Template\Factory::class,
                        'arguments' => [$this->resources['templates']]
                    ]
                ]
            ],
            'services' => [
                'Nette\Bridges\ApplicationLatte\TemplateFactory'
            ]
        ];
    }

    protected function startup()
    {
        $this->setupLatte();
    }

    private function setupLatte()
    {
        $builder = $this->getContainerBuilder();
        $latte = $builder->addDefinition('nette.latteFactory')
            ->setClass('Latte\Engine')
            ->addSetup('setTempDirectory', [$builder->expand('%tempDir%/cache/latte')])
            ->addSetup('setAutoRefresh', [$builder->expand('%debugMode%')])
            ->setImplement('Nette\Bridges\ApplicationLatte\ILatteFactory');
        foreach ($this->resources['filters'] as $name => $filter) {
            $latte->addSetup('addFilter', [$name, $filter]);
        }
    }

}
