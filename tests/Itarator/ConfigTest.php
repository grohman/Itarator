<?php
namespace Itarator;


class ConfigTest extends \PHPUnit_Framework_TestCase
{
	public function test_ReturnSelf()
	{
		$config = new Config();
		
		$this->assertSame($config, $config->setFilter($this->createMock(IFilter::class)));
		$this->assertSame($config, $config->setConsumer($this->createMock(IConsumer::class)));
	}
	
	
	public function test_setFilter_FileAndDirFilterSet()
	{
		$filter = $this->createMock(IFilter::class);
		
		$config = new Config();
		$config->setFilter($filter);
		
		$this->assertSame($filter, $config->FileFilter);
		$this->assertSame($filter, $config->DirFilter);
	}
	
	public function test_setConsumer_FileAndDirConsumerSet()
	{
		$consumer = $this->createMock(IConsumer::class);
		
		$config = new Config();
		$config->setConsumer($consumer);
		
		$this->assertSame($consumer, $config->FileConsumer);
		$this->assertSame($consumer, $config->DirConsumer);
	}
}