<?php
namespace Itarator\Filters;


class NameRegexFilterTest extends \PHPUnit_Framework_TestCase
{
	public function test_getRegex_RegexReturned()
	{
		$subject = new NameRegexFilter('a');
		$this->assertEquals('a', $subject->getRegex());
	}
	
	
	public function test_filter_RegexMatches_ReturnTrue()
	{
		$subject = new NameRegexFilter('/a$/');
		$this->assertTrue($subject->filter('/path/a'));
	}
	
	public function test_filter_RegexNotMatches_ReturnFalse()
	{
		$subject = new NameRegexFilter('/a$/');
		$this->assertFalse($subject->filter('/path/b'));
	}
	
	public function test_filter_RegexMatchesOnlyNameAndNotHolePath_ReturnTrue()
	{
		$subject = new NameRegexFilter('/^a/');
		$this->assertTrue($subject->filter('/path/ab'));
	}
	
	public function test_filter_RegexMatchesFileNameWithType_ReturnTrue()
	{
		$subject = new NameRegexFilter('/.*.php/');
		$this->assertTrue($subject->filter('/path/ab.php'));
	}
	
	public function test_filter_RegexMatchesDirNameAnd_ReturnTrue()
	{
		$subject = new NameRegexFilter('/^a/');
		$this->assertTrue($subject->filter('/path/ab/'));
	}
	
	public function test_filter_RegexMatchesPathButNotName_ReturnFalse()
	{
		$subject = new NameRegexFilter('/^path/');
		$this->assertFalse($subject->filter('path/ab'));
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function test_filter_RegexInvalid_ErrorThrown()
	{
		$subject = new NameRegexFilter('abc');
		$subject->filter('a');
	}
}