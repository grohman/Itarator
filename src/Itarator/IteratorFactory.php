<?php
namespace Itarator;


class IteratorFactory
{
	/**
	 * @return RecursiveIterator
	 */
	public function get()
	{
		return new RecursiveIterator();
	}
}