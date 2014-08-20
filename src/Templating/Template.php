<?php

namespace WebEdit\Templating;

use Nette\Application\UI;

final class Template extends UI\PresenterComponent implements \Iterator {

    private $view;
    private $reflection;
    private $appDir;

    public function __construct($view) {
        $this->view = $view === 'layout' ? '@' . $view : $view;
    }

    public function __toString() {
        $this->rewind();
        return $this->current();
    }

    public function rewind() {
        if (!$this->reflection || $this->view !== '@layout') {
            $this->reflection = $this->parent->parent->getReflection();
        }
        $this->appDir = $this->presenter->context->parameters['appDir'];
    }

    public function current() {
        do {
            $file = '/' . $this->view . '.latte';
            $localTemplate = $this->appDir . '/src/' . str_replace('\\', '/', $this->reflection->getName()) . $file;
            $template = dirname($this->reflection->getFileName()) . '/' . $this->reflection->getShortName() . $file;
            $this->reflection = $this->reflection->getParentClass();
            if (file_exists($localTemplate)) {
                return $localTemplate;
            } elseif (file_exists($template)) {
                return $template;
            }
        } while ($this->valid());
    }

    public function key() {
        
    }

    public function next() {
        
    }

    public function valid() {
        return $this->reflection;
    }

}
