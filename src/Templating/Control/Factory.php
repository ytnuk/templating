<?php
namespace Ytnuk\Templating\Control;

use Ytnuk;

interface Factory
{

	public function create() : Ytnuk\Templating\Control;
}
