<?php
namespace Itarator\Filters;


class WildcardFilterTest extends \PHPUnit_Framework_TestCase
{
	public function test_add_ReturnSelf()
	{
		$subject = new WildcardFilter();
		
		$this->assertSame($subject, $subject->addPattern(['a']));
		$this->assertSame($subject, $subject->addPattern('a'));
	}
	
	
	public function test_filter_EmptyString_NoException()
	{
		$subject = new WildcardFilter('a');
		$this->assertFalse($subject->filter(''));
	}
	
	public function test_filter_MatchNothing_ReturnFalse()
	{
		$subject = new WildcardFilter(['a', 'b']);
		$this->assertFalse($subject->filter('c'));
	}
	
	public function test_filter_MatchOne_ReturnTrue()
	{
		$subject = new WildcardFilter(['a', 'b', 'c']);
		$this->assertTrue($subject->filter('b'));
	}
	
	
	public function test_add_AsString_MatchPatternsCorrectly()
	{
		$subject = new WildcardFilter();
		$subject->addPattern('a');
		$subject->addPattern('b');
		$subject->addPattern('c');
		
		$this->assertTrue($subject->filter('b'));
		$this->assertFalse($subject->filter('d'));
	}
	
	
	public function test_add_AsArray_MatchPatternsCorrectly()
	{
		$subject = new WildcardFilter();
		$subject->addPattern(['a', 'b']);
		$subject->addPattern(['c', 'd']);
		
		$this->assertTrue($subject->filter('b'));
		$this->assertFalse($subject->filter('e'));
	}
	
	
	public function test_filter_MatchComma_ReturnTrue()
	{
		$subject = new WildcardFilter(['a.b']);
		$this->assertTrue($subject->filter('a.b'));
	}
	
	public function test_filter_NotMatchingComma_ReturnFalse()
	{
		$subject = new WildcardFilter(['a.b']);
		$this->assertFalse($subject->filter('aab'));
	}
	
	public function test_filter_NotMatchingCommaInSubject_ReturnFalse()
	{
		$subject = new WildcardFilter(['aab']);
		$this->assertFalse($subject->filter('a.b'));
	}
	
	public function test_filter_MatchBackslash_ReturnTrue()
	{
		$subject = new WildcardFilter(['a\b']);
		$this->assertTrue($subject->filter('a\b'));
	}
	
	public function test_filter_MatchAsterisk_ReturnTrue()
	{
		$subject = new WildcardFilter(['a*b']);
		$this->assertTrue($subject->filter('anmb'));
	}
	
	public function test_filter_CaseSensitive()
	{
		$subject = new WildcardFilter(['ab']);
		$this->assertFalse($subject->filter('aB'));
		$this->assertTrue($subject->filter('ab'));
	}
}