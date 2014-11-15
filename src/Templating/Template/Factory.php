<?php

namespace WebEdit\Templating\Template;

use Nette;
use WebEdit\Application;
use WebEdit\Templating;

/**
 * Class Factory
 *
 * @package WebEdit\Templating
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
			return new Templating\Template($view, $this->templates);
		});
	}
}
