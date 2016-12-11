<?php
namespace Itarator\Consumers;


use Itarator\IConsumer;


class CallbackConsumer implements IConsumer
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
	 */
	public function consume($path)
	{
		$callback = $this->callback;
		$callback($path);
	}
}