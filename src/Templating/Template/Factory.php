<?php
namespace Ytnuk\Templating\Template;

use Nette;
use Ytnuk;

/**
 * Class Factory
 *
 * @package Ytnuk\Templating
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
		return new Nette\Application\UI\Multiplier(
			function (
				$view,
				Nette\Application\UI\Multiplier $multiplier
			) {
				return new Ytnuk\Templating\Template(
					$view,
					$this->templates,
					get_class($multiplier->getParent())
				);
			}
		);
	}
}
