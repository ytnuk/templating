<?php

namespace WebEdit\Templating;

use WebEdit\Reflection;
use Nette\Application\UI;

final class Template extends UI\PresenterComponent implements \Iterator {

    private $view;
    private $templates = [];

    public function __construct($view) {
        $this->view = $view == 'layout' ? '@' . $view : $view;
    }

    public function __toString() {
        $this->rewind();
        return $this->current();
    }

    public function rewind() { //TODO
        if (!$this->templates) {
            $reflection = new Reflection($this->parent->parent);
            $local = $this->presenter->context->parameters['appDir'] . '/src';
            do {
                $localTemplate = $local . '/' . $reflection->getModuleName($reflection->getShortName() . '/' . $this->view . '.latte', '/', FALSE);
                $path = pathinfo($reflection->getFileName());
                $template = $path['dirname'] . '/' . $path['filename'] . '/' . $this->view . '.latte';
                if (file_exists($localTemplate)) {
                    $this->templates[] = $localTemplate;
                } elseif (file_exists($template)) {
                    $this->templates[] = $template;
                }
            } while ($reflection = $reflection->getParentClass());
        }
        return reset($this->templates);
    }

    public function current() {
        return array_shift($this->templates);
    }

    public function key() {
        return key($this->templates);
    }

    public function next() {
        return next($this->templates);
    }

    public function valid() {
        return $this->key() !== NULL;
    }

}
