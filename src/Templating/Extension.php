<?php

namespace WebEdit\Templating;

use WebEdit\Bootstrap;
use WebEdit\Templating;

final class Extension extends Bootstrap\Extension {

    private $resources = [
        'filters' => []
    ];

    public function beforeCompile() {
        $this->loadResources();
        $this->setupFilters();
    }

    private function loadResources() {
        $this->resources = $this->getConfig($this->resources);
        foreach ($this->compiler->getExtensions() as $extension) {
            if (!$extension instanceof Templating\Provider) {
                continue;
            }
            $this->resources = array_merge_recursive($this->resources, $extension->getTemplatingResources());
        }
    }

    private function setupFilters() {
        $builder = $this->getContainerBuilder();
        $latteFactory = $builder->getDefinition('nette.latteFactory');
        foreach ($this->resources['filters'] as $name => $filter) {
            $latteFactory->addSetup('addFilter', [$name, $filter]);
        }
    }

}
