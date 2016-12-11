<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class CallbackFilter implements IFilter
{
	/** @var callable */
	private $callback;
	
	
	/**
	 * @param callable $callback
	 */
	public function __construct($callback)
	{
		$this->callback = $callback;
	}
	
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		$callback = $this->callback;
		return $callback($path);
	}
}