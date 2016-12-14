<?php
namespace Itarator\Filters;


use Itarator\IFilter;


class AllFilter implements IFilter
{
	/** @var IFilter[] */
	private $filters = [];
	
	/**
	 * @param IFilter|IFilter[] $filter
	 * @return static
	 */
	public function add($filter)
	{
		if (is_array($filter))
		{
			$this->filters = array_merge($this->filters, $filter);
		}
		else
		{
			$this->filters[] = $filter;
		}
		
		return $this;
	}

	/**
	 * @param string $path
	 * @return bool True if this item should be consumer
	 */
	public function filter($path)
	{
		foreach ($this->filters as $filter)
		{
			if (!$filter->filter($path)) 
				return false;
		}
		
		return true;
	}
}