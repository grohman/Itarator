<?php
namespace Itarator\Filters;


use Itarator\IFilter;

class AllFilterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return IFilter|\PHPUnit_Framework_MockObject_MockObject
	 */
	private function mockFilter($result = true)
	{
		$filter = $this->createMock(IFilter::class);
		$filter->method('filter')->willReturn($result);
		return $filter;
	}
	
	
	public function test_add_ReturnSelf()
	{
		$filter = $this->mockFilter();
		$subject = new AllFilter();
		
		$this->assertSame($subject, $subject->add($filter));
	}
	
	public function test_filter_PassedFiltersAsArray_FiltersCalled()
	{
		$filter1 = $this->mockFilter();
		$filter2 = $this->mockFilter();
		
		$subject = new AllFilter();
		$subject->add([$filter1, $filter2]);
		
		$filter1->expects($this->once())->method('filter')->with('a')->willReturn(true);
		$filter2->expects($this->once())->method('filter')->with('a')->willReturn(true);
		
		$subject->filter('a');
	}
	
	public function test_filter_PassedFiltersAsSingleItem_FiltersCalled()
	{
		$filter1 = $this->mockFilter();
		$filter2 = $this->mockFilter();
		
		$subject = new AllFilter();
		$subject->add($filter1);
		$subject->add($filter2);
		
		$filter1->expects($this->once())->method('filter')->with('a')->willReturn(true);
		$filter2->expects($this->once())->method('filter')->with('a')->willReturn(true);
		
		$subject->filter('a');
	}
	
	public function test_filter_AllReturnTrue_ReturnTrue()
	{
		$subject = new AllFilter();
		$subject->add($this->mockFilter(true));
		$subject->add($this->mockFilter(true));
		
		$this->assertTrue($subject->filter('a'));
	}
	
	public function test_filter_FilterReturnsFalse_ReturnFalse()
	{
		$subject = new AllFilter();
		$subject->add($this->mockFilter(false));
		$this->assertFalse($subject->filter('a'));
	}
	
	public function test_filter_FilterReturnFalse_NextFilterNotCalled()
	{
		$last = $this->mockFilter();
		$last->expects($this->never())->method('filter');
		
		$subject = new AllFilter();
		$subject->add($this->mockFilter(false));
		$subject->add($last);
		
		$subject->filter('a');
	}
}