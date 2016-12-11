<?php
use Itarator\Config;
use Itarator\Consumers\CallbackConsumer;
use Itarator\IFilter;
use Itarator\IConsumer;
use Itarator\IteratorFactory;
use Itarator\RecursiveIterator;
use Itarator\Filters\CallbackFilter;
use Itarator\Filters\NameRegexFilter;


class ItaratorTest extends PHPUnit_Framework_TestCase
{
	private function assertObjectPassedToConfig($objectClassName, $setMethod, $configProperties)
	{
		$object = $this->createMock($objectClassName);
		
		$subject = new Itarator();
		$subject->$setMethod($object);
		
		$config = $subject->getConfig();
		
		foreach ($configProperties as $name)
		{
			$this->assertSame($object, $config->$name);
		}
	}
	
	private function mockFactory($item = null)
	{
		$factory = $this->createMock(IteratorFactory::class);
		
		if (!$item)
			$item = $this->createMock(RecursiveIterator::class);
		
		$factory->method('get')->willReturn($item);
		return $factory;
	}
	
	private function assertSetFilterObjectPassedToConfig($setMethod, array $properties = ['DirFilter', 'FileFilter'])
	{
		$this->assertObjectPassedToConfig(IFilter::class, $setMethod, $properties);
	}
	
	private function assertSetConsumerObjectPassedToConfig($setMethod, array $properties = ['DirConsumer', 'FileConsumer'])
	{
		$this->assertObjectPassedToConfig(IConsumer::class, $setMethod, $properties);
	}
	
	
	public function test_ReturnSelf()
	{
		$consumer = $this->createMock(IConsumer::class);
		
		$subject = new Itarator();
		$this->assertSame($subject, $subject->setFilter('a'));
		$this->assertSame($subject, $subject->setFileFilter('a'));
		$this->assertSame($subject, $subject->setDirectoryFilter('a'));
		
		$this->assertSame($subject, $subject->setCallbackFilter(function() {}));
		$this->assertSame($subject, $subject->setCallbackFilesFilter(function() {}));
		$this->assertSame($subject, $subject->setCallbackDirectoriesFilter(function() {}));
		
		$this->assertSame($subject, $subject->setConsumer($consumer));
		$this->assertSame($subject, $subject->setFileConsumer($consumer));
		$this->assertSame($subject, $subject->setDirectoryConsumer($consumer));
		
		$this->assertSame($subject, $subject->setCallbackConsumer(function() {}));
		$this->assertSame($subject, $subject->setCallbackFilesConsumer(function() {}));
		$this->assertSame($subject, $subject->setCallbackDirectoriesConsumer(function() {}));
		
		$this->assertSame($subject, $subject->setRootDirectory('/'));
		$this->assertSame($subject, $subject->setRelativeDirectory('/'));
	}
	
	
	public function test_getConfig_ConfigObjectReturned()
	{
		$subject = new Itarator();
		$this->assertInstanceOf(Config::class, $subject->getConfig());
	}
	
	
	public function test_setFilter_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetFilterObjectPassedToConfig('setFilter', ['DirFilter', 'FileFilter']);
	}
	
	public function test_setFileFilter_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetFilterObjectPassedToConfig('setFileFilter', ['FileFilter']);
	}
	
	public function test_setDirectoryFilter_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetFilterObjectPassedToConfig('setDirectoryFilter', ['DirFilter']);
	}
	
	
	public function test_setConsumer_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetConsumerObjectPassedToConfig('setConsumer', ['DirConsumer', 'FileConsumer']);
	}
	
	public function test_setFileConsumer_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetConsumerObjectPassedToConfig('setFileConsumer', ['FileConsumer']);
	}
	
	public function test_setDirectoryConsumer_ObjectPassed_ObjectSetInConfig()
	{
		$this->assertSetConsumerObjectPassedToConfig('setDirectoryConsumer', ['DirConsumer']);
	}
	
	
	public function test_setFilter_StringPassed_NameRegexFilterCreated()
	{
		$subject = new Itarator();
		$subject->setFilter('a');
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(NameRegexFilter::class, $config->DirFilter);
		$this->assertInstanceOf(NameRegexFilter::class, $config->FileFilter);
	}
	
	public function test_setFilter_StringPassed_StringTreatedAsWildCard()
	{
		$subject = new Itarator();
		$subject->setFilter('*.a');
		
		$config = $subject->getConfig();
		
		/** @var NameRegexFilter $filter */
		$filter =  $config->FileFilter;
		$this->assertEquals('/.*\.a/', $filter->getRegex());
	}
	
	
	public function test_setCallbackFilter_CallbackFilterPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackFilter(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackFilter::class, $config->DirFilter);
		$this->assertInstanceOf(CallbackFilter::class, $config->FileFilter);
	}
	
	public function test_setCallbackFilesFilter_CallbackFilterPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackFilesFilter(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackFilter::class, $config->FileFilter);
	}
	
	public function test_setCallbackDirectoryFilter_CallbackFilterPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackDirectoriesFilter(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackFilter::class, $config->DirFilter);
	}
	
	
	public function test_setCallbackConsumer_CallbackConsumerPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackConsumer(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackConsumer::class, $config->DirConsumer);
		$this->assertInstanceOf(CallbackConsumer::class, $config->FileConsumer);
	}
	
	public function test_setCallbackFilesConsumer_CallbackConsumerPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackFilesConsumer(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackConsumer::class, $config->FileConsumer);
	}
	
	public function test_setCallbackDirectoryConsumer_CallbackConsumerPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setCallbackDirectoriesConsumer(function() {});
		
		$config = $subject->getConfig();
		$this->assertInstanceOf(CallbackConsumer::class, $config->DirConsumer);
	}
	
	public function test_setRootDirectory_DirPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$this->assertEquals(__DIR__, $subject->getConfig()->RootDir);
	}
	
	public function test_setRootDirectory_RelativePathSetToRootDir()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$this->assertEquals(__DIR__, $subject->getConfig()->RelativePath);
	}
	
	public function test_setRootDirectory_RelativePathReturned_RealPathPassed()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__ . '/../tests');
		$this->assertEquals(__DIR__, $subject->getConfig()->RootDir);
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_setRootDirectory_IncorrectPathPassed_ErrorThrown()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__ . '/unexisting');
	}
	
	
	public function test_setRelativeDirectory_RelativePathPassedToObject()
	{
		$path = realpath(__DIR__ . '/..');
		
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$subject->setRelativeDirectory($path);
		
		$this->assertEquals($path, $subject->getConfig()->RelativePath);
	}
	
	public function test_setRelativeDirectory_RelativePathPassed_FullPathPassedToConfig()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$subject->setRelativeDirectory(__DIR__ . '/..');
		
		$this->assertEquals(realpath(__DIR__ . '/..'), $subject->getConfig()->RelativePath);
	}
	
	public function test_setRelativeDirectory_RelativePathEqualsToRootPath_NoErrors()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$subject->setRelativeDirectory(__DIR__);
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_setRelativeDirectory_RootDirectoryNotSet_ExceptionThrown()
	{
		$subject = new Itarator();
		$subject->setRelativeDirectory(__DIR__);
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_setRelativeDirectory_InvalidDirectory_ExceptionThrown()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$subject->setRelativeDirectory(__DIR__ . '/../invalid');
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_setRelativeDirectory_RelativeDirectoryIsNotPartOfTheRootPart_ExceptionThrown()
	{
		$subject = new Itarator();
		$subject->setRootDirectory(__DIR__);
		$subject->setRelativeDirectory(__DIR__ . '/../src');
	}
	
	
	public function test_get_RecursiveIteratorReturned()
	{
		$subject = new Itarator();
		$this->assertInstanceOf(RecursiveIterator::class, $subject->get());
	}
	
	public function test_get_NewInstanceReturnedEachTime()
	{
		$subject = new Itarator();
		$this->assertNotSame($subject->get(), $subject->get());
	}
	
	public function test_get_FactoryCalled()
	{
		$factory = $this->mockFactory();
		$subject = new Itarator();
		$subject->setFactory($factory);
		
		$factory->expects($this->once())->method('get');
		
		$subject->get();
	}
	
	public function test_get_ConfigPassedToCreatedInstance()
	{
		$item = $this->createMock(RecursiveIterator::class);
		$factory = $this->mockFactory($item);
		
		$subject = new Itarator();
		$subject->setFactory($factory);
		
		$factory->method('get')->willReturn($item);
		$item->expects($this->once())->method('setConfig')->with($subject->getConfig())->willReturnSelf();
			
		$subject->get();
	}
	
	public function test_execute_RunOnRecursiveIteratorCalled()
	{
		$item = $this->createMock(RecursiveIterator::class);
		$factory = $this->mockFactory($item);
		
		$subject = new Itarator();
		$subject->setFactory($factory);
		
		$factory->method('get')->willReturn($item);
		$item->expects($this->once())->method('run');
			
		$subject->execute();
	}
}