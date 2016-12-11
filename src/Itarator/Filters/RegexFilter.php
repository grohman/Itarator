<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class RegexFilter implements IFilter
{
	private $regex;
	
	
	/**
	 * @param string $regex
	 */
	public function __construct($regex)
	{
		$this->regex = $regex;
	}
	
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		try
		{
			$result = preg_match($this->regex, $path);
		}
		catch (\Exception $e)
		{
			throw new \Exception('Invalid regex: ' . $this->regex, 0, $e);
		}
		
		if ($result === false)
		{
			throw new \Exception('Invalid regex: ' . $this->regex);
		}
		
		return $result === 1;
	}

	/**
	 * @return string
	 */
	public function getRegex()
	{
		return $this->regex;
	}
}