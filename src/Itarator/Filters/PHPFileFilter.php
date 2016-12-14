<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class PHPFileFilter implements IFilter
{
	
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		return (preg_match('/^ph(p[3457]?|t|tml|ps)$/', strtolower($ext)) === 1);
	}
}