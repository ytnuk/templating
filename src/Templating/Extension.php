<?php

namespace WebEdit\Templating;

use WebEdit\Module;
use WebEdit\Templating;

final class Extension extends Module\Extension implements Templating\Provider {

    public function beforeCompile() {
        $this->setupFilters();
    }

    private function setupFilters() {
        $builder = $this->getContainerBuilder();
        $latteFactory = $builder->getDefinition('nette.latteFactory');
        foreach ($this->resources['filters'] as $name => $filter) {
            $latteFactory->addSetup('addFilter', [$name, $filter]);
        }
    }

    public function getTemplatingResources() {
        return [
            'filters' => []
        ];
    }

}
