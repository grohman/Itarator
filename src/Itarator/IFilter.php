<?php
namespace Itarator;


interface IFilter
{
	/**
	 * @param string $path
	 */
	public function filter($path);
}