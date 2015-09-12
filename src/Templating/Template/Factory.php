<?php
namespace Ytnuk\Templating\Template;

use Nette;
use Ytnuk;

final class Factory
	implements Ytnuk\Application\Control\Factory
{

	/**
	 * @var array
	 */
	private $templates = [];

	public function __construct(array $templates)
	{
		$this->templates = $templates;
	}

	public function create() : Nette\Application\UI\Multiplier
	{
		return new Nette\Application\UI\Multiplier(
			function (
				$view,
				Nette\Application\UI\Multiplier $multiplier
			) : Ytnuk\Templating\Template {
				return new Ytnuk\Templating\Template(
					$view,
					$this->templates,
					get_class($multiplier->getParent())
				);
			}
		);
	}
}
