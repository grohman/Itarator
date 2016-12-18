<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class SequenceFilter implements IFilter
{
	private $list = [];
	
	
	/**
	 * @param IFilter $filter
	 * @return static
	 */
	public function match(IFilter $filter)
	{
		$this->list[] = [$filter, true];
		return $this;
	}

	/**
	 * @param IFilter $filter
	 * @return static
	 */
	public function noMatch(IFilter $filter)
	{
		$this->list[] = [$filter, false];
		return $this;
	}
	
	/**
	 * @param string $path
	 * @return bool
	 */
	public function filter($path)
	{
		for ($i = count($this->list) - 1; $i >= 0; $i--)
		{
			/** @var IFilter $filter */
			$filter = $this->list[$i][0];
			
			if ($filter->filter($path))
				return $this->list[$i][1];
		}
		
		return false;
	}
}