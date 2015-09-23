<?php
namespace Ytnuk\Templating;

use Nette;
use Ytnuk;

final class Control
	extends Nette\Application\UI\PresenterComponent
{

	const NAME = 'templating';

	/**
	 * @var array
	 */
	private $templates;

	public function __construct(array $templates = [])
	{
		parent::__construct();
		$this->templates = $templates;
	}

	protected function createComponent($name) : Nette\ComponentModel\IComponent
	{
		return new Template(
			$name,
			$this->templates,
			get_class($this->getParent())
		) ? : parent::createComponent($name);
	}
}
