<?php

namespace WebEdit\Templating\Template;

use WebEdit\Application;
use WebEdit\Templating;

final class Factory
{

    private $templates;

    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    public function create()
    {
        return new Application\Control\Multiplier(function ($view) {
            return new Templating\Template($view, $this->templates);
        });
    }

}
