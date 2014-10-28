<?php

namespace WebEdit\Templating;

use Nette\ComponentModel;
use ReflectionClass;
use WebEdit\Application;

/**
 * @property-read Application\Control\Multiplier $parent
 */
final class Template extends ComponentModel\Component implements \Iterator
{

	private $view;
	private $templates;
	/**
	 * @var ReflectionClass
	 */
	private $reflection;

	public function __construct($view, $templates)
	{
		$this->view = $view === 'layout' ? '@' . $view : $view;
		$this->templates = $templates;
	}

	public function __toString()
	{
		$this->rewind();

		return $this->current();
	}

	public function rewind()
	{
		if ( ! $this->reflection || $this->view !== '@layout') {
			$this->reflection = $this->parent->parent->getReflection();
		}
	}

	public function current()
	{
		do {
			$templates = array_map(function ($template) {
				return $template . '/' . str_replace('\\', '/', $this->reflection->getName());
			}, $this->templates);
			$templates[] = dirname($this->reflection->getFileName()) . '/' . $this->reflection->getShortName();
			$this->reflection = $this->reflection->getParentClass();
			$file = '/' . $this->view . '.latte';
			foreach ($templates as $template) {
				if (file_exists($template . $file)) {
					return $template . $file;
				}
			}
		} while ($this->valid());

		return NULL;
	}

	public function valid()
	{
		return $this->reflection;
	}

	public function key()
	{
	}

	public function next()
	{
	}
}
