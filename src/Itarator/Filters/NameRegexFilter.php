<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class NameRegexFilter implements IFilter
{
	/** @var RegexFilter */
	private $regexFilter;
	
	
	/**
	 * @param string $regex
	 */
	public function __construct($regex)
	{
		$this->regexFilter = new RegexFilter($regex);
	}
	
	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		return $this->regexFilter->filter(basename($path));
	}

	/**
	 * @return string
	 */
	public function getRegex()
	{
		return $this->regexFilter->getRegex();
	}
}