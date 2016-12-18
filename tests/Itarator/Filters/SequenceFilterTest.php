<?php
namespace Itarator\Filters;


use Itarator\IFilter;

class SequenceFilterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param bool $result
	 * @return IFilter|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockFilter($result = null)
	{
		$filter = $this->createMock(IFilter::class);
		
		if ($result !== null) 
		{
			$filter->method('filter')->willReturn($result);
		}
		
		return $filter;
	}
	
	
	public function test_ReturnSelf()
	{
		$subject = new SequenceFilter();
		$this->assertSame($subject, $subject->match($this->mockFilter()));
		$this->assertSame($subject, $subject->noMatch($this->mockFilter()));
	}
	
	public function test_filter_NoFilters_ReturnFalse()
	{
		$subject = new SequenceFilter();
		$this->assertFalse($subject->filter('a'));
	}
	
	public function test_filter_NoMatchingFilter_ReturnFalse()
	{
		$subject = new SequenceFilter();
		
		$subject->match($this->mockFilter(false));
		$subject->noMatch($this->mockFilter(false));
		
		$this->assertFalse($subject->filter('a'));
	}
	
	public function test_filter_MatchingTrueFilter_ReturnTrue()
	{
		$subject = new SequenceFilter();
		
		$subject->match($this->mockFilter(true));
		
		$this->assertTrue($subject->filter('a'));
	}
	
	public function test_filter_MatchingFalseFilter_ReturnFalse()
	{
		$subject = new SequenceFilter();
		
		$subject->noMatch($this->mockFilter(true));
		
		$this->assertFalse($subject->filter('a'));
	}
	
	public function test_filter_MatchingFalseAfterTrueFilter_ReturnFalse()
	{
		$subject = new SequenceFilter();
		
		$subject->match($this->mockFilter(true));
		$subject->noMatch($this->mockFilter(true));
		
		$this->assertFalse($subject->filter('a'));
	}
	
	public function test_filter_MatchingTrueAfterFalseFilter_ReturnTrue()
	{
		$subject = new SequenceFilter();
		
		$subject->noMatch($this->mockFilter(true));
		$subject->match($this->mockFilter(true));
		
		$this->assertTrue($subject->filter('a'));
	}
	
	public function test_filter_MatchingLastTrueFilter_ReturnTrue()
	{
		$subject = new SequenceFilter();
		
		$subject->noMatch($this->mockFilter(true));
		$subject->match($this->mockFilter(true));
		$subject->noMatch($this->mockFilter(false));
		$subject->match($this->mockFilter(false));
		
		$this->assertTrue($subject->filter('a'));
	}
	
	public function test_filter_MatchingLastFalseFilter_ReturnTrue()
	{
		$subject = new SequenceFilter();
		
		$subject->match($this->mockFilter(true));
		$subject->noMatch($this->mockFilter(true));
		$subject->noMatch($this->mockFilter(false));
		$subject->match($this->mockFilter(false));
		
		$this->assertFalse($subject->filter('a'));
	}
}