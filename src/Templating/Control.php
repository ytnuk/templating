<?php
namespace Ytnuk\Templating;

use Nette;
use Ytnuk;

final class Control
	extends Nette\Application\UI\PresenterComponent
{

	/**
	 * @var array
	 */
	private $templates;

	public function __construct(array $templates = [])
	{
		parent::__construct();
		$this->templates = $templates;
	}

	public function getTemplates() : array
	{
		return $this->templates;
	}

	public function setTemplates(array $templates)
	{
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
