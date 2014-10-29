<?php

namespace WebEdit\Templating\Template;

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
	 * @return Application\Control\Multiplier
	 */
	public function create()
	{
		return new Application\Control\Multiplier(function ($view) {
			return new Templating\Template($view, $this->templates);
		});
	}
}
