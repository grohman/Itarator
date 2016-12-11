<?php
namespace Itarator;


class IteratorFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function test_get_RecursiveIteratorObjectReturned()
	{
		$this->assertInstanceOf(RecursiveIterator::class, (new IteratorFactory())->get());
	}
	
	public function test_get_NewObjectReturned()
	{
		$subject = new IteratorFactory();
		$this->assertNotSame($subject->get(), $subject->get());
	}
}