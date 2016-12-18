<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class WildcardFilter implements IFilter
{
	private $patterns = [];
	
	
	/**
	 * @param array|string $pattern
	 */
	public function __construct($pattern = [])
	{
		$this->addPattern($pattern);
	}
	
	
	/**
	 * @param string|array $pattern
	 * @return static
	 */
	public function addPattern($pattern)
	{
		if (is_string($pattern))
			return $this->addPattern([$pattern]);
		
		$this->patterns = array_merge($this->patterns, $pattern);
		
		return $this;
	}
	
	
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		foreach ($this->patterns as $pattern)
		{
			if (fnmatch($pattern, $path, FNM_NOESCAPE))
				return true;
		}
		
		return false;
	}
}