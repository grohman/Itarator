<?php
namespace Itarator\Filters;


class RegexFilterTest extends \PHPUnit_Framework_TestCase
{
	public function test_getRegex_RegexReturned()
	{
		$subject = new RegexFilter('a');
		$this->assertEquals('a', $subject->getRegex());
	}
	
	
	public function test_filter_RegexMatches_ReturnTrue()
	{
		$subject = new RegexFilter('/a$/');
		$this->assertTrue($subject->filter('/path/a'));
	}
	
	public function test_filter_RegexNotMatches_ReturnFalse()
	{
		$subject = new RegexFilter('/a$/');
		$this->assertFalse($subject->filter('/path/b'));
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function test_filter_RegexInvalid_ErrorThrown()
	{
		$subject = new RegexFilter('abc');
		$subject->filter('a');
	}
}