<?php
namespace Itarator\Consumers;


class CallbackConsumerTest extends \PHPUnit_Framework_TestCase
{
	public function test_CallbackIsCalled()
	{
		$isCalled = false;
		
		$Consumer = new CallbackConsumer(function() use (&$isCalled) { $isCalled = true; });
		$Consumer->consume('a');
		
		$this->assertTrue($isCalled);
	}
	
	public function test_CallbackIsCalledWithCorrectParams()
	{
		$calledWith = [];
		$expected = ['a', 'b', 'c'];
		
		$Consumer = new CallbackConsumer(function($item) use (&$calledWith) { $calledWith[] = $item; });
		
		foreach ($expected as $item)
		{
			$Consumer->consume($item);
		}
		
		$this->assertEquals($expected, $calledWith);
	}
}