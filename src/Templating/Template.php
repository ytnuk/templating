<?php

namespace Ytnuk\Templating;

use Iterator;
use Nette;
use ReflectionClass;
use Ytnuk;

/**
 * Class Template
 *
 * @package Ytnuk\Templating
 */
final class Template extends Nette\ComponentModel\Component implements Iterator
{

	/**
	 * @var string
	 */
	private $view;

	/**
	 * @var array
	 */
	private $templates;

	/**
	 * @var ReflectionClass
	 */
	private $reflection;

	/**
	 * @param string $view
	 * @param array $templates
	 */
	public function __construct($view, array $templates)
	{
		$this->view = $view === 'layout' ? '@' . $view : $view;
		$this->templates = $templates;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$this->rewind();

		return (string) $this->current();
	}

	public function rewind()
	{
		if ( ! $this->reflection || $this->view !== '@layout') {
			$this->reflection = $this->getParent()
				->getParent()
				->getReflection();
		}
	}

	/**
	 * @return string|NULL
	 */
	public function current()
	{
		do {
			$templates = array_map(function ($template) {
				$namespace = explode('\\', $this->reflection->getName());
				$namespace[key($namespace)] = $template;

				return implode('/', $namespace);
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

		return FALSE;
	}

	/**
	 * @return ReflectionClass
	 */
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
