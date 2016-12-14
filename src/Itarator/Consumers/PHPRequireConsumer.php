<?php
namespace Itarator\Consumers;


use Itarator\IConsumer;


class PHPRequireConsumer implements IConsumer
{
	/**
	 * @param string $path
	 */
	public function consume($path)
	{
		require_once $path;
	}
}