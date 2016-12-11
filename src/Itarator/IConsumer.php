<?php
namespace Itarator;


interface IConsumer
{
	/**
	 * @param string $path
	 */
	public function consume($path);
}