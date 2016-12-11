<?php
namespace Itarator\Filters;


class CallbackFilterTest extends \PHPUnit_Framework_TestCase
{
	public function test_CallbackIsCalled()
	{
		$isCalled = false;
		
		$filter = new CallbackFilter(function() use (&$isCalled) { $isCalled = true; });
		$filter->filter('a');
		
		$this->assertTrue($isCalled);
	}
	
	public function test_CallbackIsCalledWithCorrectParams()
	{
		$calledWith = [];
		$expected = ['a', 'b', 'c'];
		
		$filter = new CallbackFilter(function($item) use (&$calledWith) { $calledWith[] = $item; });
		
		foreach ($expected as $item)
		{
			$filter->filter($item);
		}
		
		$this->assertEquals($expected, $calledWith);
	}
	
	public function test_ReturnedValueFromFilterIsReturned()
	{
		$filterFalse = new CallbackFilter(function() { return false; });
		$filterTrue = new CallbackFilter(function() { return true; });
		
		$this->assertFalse($filterFalse->filter('a'));
		$this->assertTrue($filterTrue->filter('a'));
	}
}