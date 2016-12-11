<?php
namespace Itarator;


interface IFilter
{
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path);
}