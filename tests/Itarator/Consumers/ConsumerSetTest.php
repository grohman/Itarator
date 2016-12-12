<?php
namespace Itarator\Consumers;


use Itarator\IConsumer;


class ConsumerSetTest extends \PHPUnit_Framework_TestCase
{
	public function test_add_ReturnsSelf()
	{
		$subject = new ConsumerSet();
		$this->assertSame($subject, $subject->add(function() {}));
	}
	
	public function test_add_SingleElement_ConsumerAdded()
	{
		$consumer = $this->createMock(IConsumer::class);
		$subject = new ConsumerSet();
		
		$subject->add($consumer);
		
		$this->assertEquals([$consumer], $subject->getSet());
	}
	
	public function test_add_ArrayOfElements_ConsumersAdded()
	{
		$consumer1 = $this->createMock(IConsumer::class);
		$consumer2 = $this->createMock(IConsumer::class);
		$subject = new ConsumerSet();
		
		$subject->add($consumer1, $consumer2);
		
		$this->assertEquals([$consumer1, $consumer2], $subject->getSet());
	}
	
	public function test_add_SingleElement_ConsumersRetainOrder()
	{
		$consumer1 = $this->createMock(IConsumer::class);
		$consumer2 = $this->createMock(IConsumer::class);
		$subject = new ConsumerSet();
		
		$subject->add($consumer1);
		$subject->add($consumer2);
		
		$this->assertEquals([$consumer1, $consumer2], $subject->getSet());
	}
	
	public function test_add_Callback_CallbackConsumerAdded()
	{
		$subject = new ConsumerSet();
		
		$subject->add(function(){});
		$set = $subject->getSet();
		
		$this->assertInstanceOf(CallbackConsumer::class, $set[0]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_add_InvalidParameter_ExceptionThrown()
	{
		$subject = new ConsumerSet();
		$subject->add(2);
	}
	
	public function test_consume_AllConsumersCalled()
	{
		$consumer1 = $this->createMock(IConsumer::class);
		$consumer2 = $this->createMock(IConsumer::class);
		$subject = new ConsumerSet();
		
		$consumer1->expects($this->once())->method('consume')->with('abc');
		$consumer2->expects($this->once())->method('consume')->with('abc');
		
		$subject->add($consumer1, $consumer2);
		
		$subject->consume('abc');
	}
	
	public function test_consume_PassedCallbackCalled()
	{
		$isCalled = false;
		$subject = new ConsumerSet();
		$subject->add(function($path) use (&$isCalled) 
		{
			$isCalled = true;
			$this->assertEquals('abc', $path);
		});
		
		$subject->consume('abc');
		
		$this->assertTrue($isCalled);
	}
}