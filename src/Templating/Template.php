<?php
namespace Ytnuk\Templating;

use Countable;
use Iterator;
use Nette;
use ReflectionClass;
use Serializable;
use Ytnuk;

final class Template
	extends Nette\ComponentModel\Component
	implements Iterator, Serializable, Countable
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

	public function __construct(
		string $view,
		array $templates,
		string $class
	) {
		parent::__construct();
		$this->view = $view;
		$this->templates = $templates;
		$this->class = $class;
	}

	public function __toString() : string
	{
		return $this->rewind();
	}

	public function current() : string
	{
		if ($this->valid()) {
			$templates = array_map(
				function ($template) {
					$namespace = explode(
						'\\',
						$this->reflection->getName()
					);
					$namespace[key($namespace)] = $template;

					return implode(
						DIRECTORY_SEPARATOR,
						$namespace
					);
				},
				$this->templates
			);
			$templates[] = implode(
				DIRECTORY_SEPARATOR,
				[
					dirname($this->reflection->getFileName()),
					$this->reflection->getShortName(),
				]
			);
			$file = $this->view . '.latte';
			foreach (
				$templates as $template
			) {
				$path = implode(
					DIRECTORY_SEPARATOR,
					[
						$template,
						$file,
					]
				);
				if (is_file($path)) {
					return $path;
				}
			}
		}

		return (string) NULL;
	}

	public function key() : string
	{
		return $this->valid() ? $this->reflection->getName() : (string) NULL;
	}

	public function next() : string
	{
		while ($this->valid() && $this->reflection = $this->reflection->getParentClass()) {
			if ($current = $this->current()) {
				return $current;
			}
		}

		return (string) NULL;
	}

	public function rewind(bool $force = FALSE) : string
	{
		if ($this->rewind || $force) {
			$this->reflection = new ReflectionClass($this->class);
			$this->rewind = ! $this->disableRewind;

			return $this->current() ? : $this->next();
		} else {
			return $this->next();
		}
	}

	public function valid() : bool
	{
		return (bool) $this->reflection;
	}

	public function disableRewind(bool $disable = TRUE) : self
	{
		if ( ! $this->disableRewind = $disable) {
			$this->rewind = ! $disable;
		}

		return $this;
	}

	public function serialize() : string
	{
		return serialize(
			[
				$this->view,
				$this->templates,
				$this->class,
				$this->key(),
			]
		);
	}

	public function unserialize($serialized)
	{
		list($this->view, $this->templates, $this->class, $reflection) = unserialize($serialized);
		$this->reflection = $reflection ? new ReflectionClass($this->class) : $reflection;
	}

	public function count() : int
	{
		$serialized = $this->serialize();
		$count = iterator_count($this);
		$this->unserialize($serialized);

		return $count;
	}
}
