<?php

namespace Kutny\Templating\Template;

use Nette;
use Kutny;

/**
 * Class Factory
 *
 * @package Kutny\Templating
 */
final class Factory
{

	/**
	 * @var array
	 */
	private $templates;

	/**
	 * @param array $templates
	 */
	public function __construct(array $templates)
	{
		$this->templates = $templates;
	}

	/**
	 * @return Nette\Application\UI\Multiplier
	 */
	public function create()
	{
		return new Nette\Application\UI\Multiplier(function ($view) {
			return new Kutny\Templating\Template($view, $this->templates);
		});
	}
}
