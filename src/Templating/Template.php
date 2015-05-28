<?php

namespace Ytnuk\Templating;

use Iterator;
use Serializable;
use Nette;
use ReflectionClass;
use Ytnuk;

/**
 * Class Template
 *
 * @package Ytnuk\Templating
 */
final class Template extends Nette\ComponentModel\Component implements Iterator, Serializable
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
	 * @var string
	 */
	private $class;

	/**
	 * @var ReflectionClass
	 */
	private $reflection;

	/**
	 * @var bool
	 */
	private $rewind = TRUE;

	/**
	 * @var bool
	 */
	private $disableRewind = FALSE;

	/**
	 * @param string $view
	 * @param array $templates
	 * @param string $class
	 */
	public function __construct($view, array $templates, $class)
	{
		$this->view = $view;
		$this->templates = $templates;
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->rewind();
	}

	/**
	 * @param bool $force
	 *
	 * @inheritdoc
	 * @return string|NULL
	 */
	public function rewind($force = FALSE)
	{
		if ($this->rewind || $force) {
			$this->reflection = new ReflectionClass($this->class);
			$this->rewind = ! $this->disableRewind;

			return $this->current() ? : $this->next();
		} else {
			return $this->next();
		}
	}

	/**
	 * @return string|NULL
	 */
	public function current()
	{
		if ($this->valid()) {
			$templates = array_map(function ($template) {
				$namespace = explode('\\', $this->reflection->getName());
				$namespace[key($namespace)] = $template;

				return implode(DIRECTORY_SEPARATOR, $namespace);
			}, $this->templates);
			$templates[] = implode(DIRECTORY_SEPARATOR, [
				dirname($this->reflection->getFileName()),
				$this->reflection->getShortName()
			]);
			$file = $this->view . '.latte';
			foreach ($templates as $template) {
				$path = implode(DIRECTORY_SEPARATOR, [
					$template,
					$file
				]);
				if (is_file($path)) {
					return $path;
				}
			}
		}

		return NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function valid()
	{
		return (bool) $this->reflection;
	}

	/**
	 * @return string|NULL
	 */
	public function next()
	{
		while ($this->valid() && $this->reflection = $this->reflection->getParentClass()) {
			if ($current = $this->current()) {
				return $current;
			}
		}

		return NULL;
	}

	/**
	 * @param bool $disable
	 *
	 * @return $this
	 */
	public function disableRewind($disable = TRUE)
	{
		if ( ! $this->disableRewind = $disable) {
			$this->rewind = ! $disable;
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function serialize()
	{
		return json_encode([
			$this->view,
			$this->templates,
			$this->class,
			$this->key()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function key()
	{
		return $this->valid() ? $this->reflection->getName() : NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function unserialize($serialized)
	{
		list($this->view, $this->templates, $this->class, $reflection) = json_decode($serialized);
		$this->reflection = $reflection ? new ReflectionClass($reflection) : $reflection;
	}
}
