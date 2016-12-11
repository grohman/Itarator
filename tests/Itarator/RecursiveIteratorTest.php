<?php
namespace Itarator;


use Itarator\Consumers\CallbackConsumer;
use Itarator\Filters\CallbackFilter;

class RecursiveIteratorTest extends \PHPUnit_Framework_TestCase
{
	const TEST_DIR = '_RecursiveIterator_TestDir'; 

	/**
	 * @param string $name
	 * @return string
	 */
	private function getTestDirectoryPath($name)
	{
		return __DIR__ . '/' . self::TEST_DIR . '/' . $name;
	}

	/**
	 * @param string $name
	 * @return Config
	 */
	private function createConfigForDir($name)
	{
		$config = new Config();
		$config->RootDir = $this->getTestDirectoryPath($name);
		$config->RelativePath = $config->RootDir;
		return $config;
	}

	/**
	 * @return CallbackConsumer
	 */
	private function createFailConsumer()
	{
		return new CallbackConsumer(function() { $this->fail(); });
	}

	/**
	 * @return CallbackFilter
	 */
	private function createFailFilter()
	{
		return new CallbackFilter(function() { $this->fail(); });
	}

	/**
	 * @param Config $config
	 * @return RecursiveIterator
	 */
	private function createSubject(Config $config)
	{
		$subject = new RecursiveIterator();
		$subject->setConfig($config);
		return $subject;
	}
	
	private function createEmptyDirectory()
	{
		$emptyPath = $this->getTestDirectoryPath('empty');
		
		if (!is_dir($emptyPath))
			mkdir($emptyPath);
	}
	
	private function assertSameFileSet($expected, $result)
	{
		$this->assertEquals($expected, array_intersect($expected, $result));
		$this->assertEquals($result, array_intersect($result, $expected));
	}
	
	
	public function test_sanity() 
	{
		$isCalled = false;
		$config = $this->createConfigForDir('sanity');
		$subject = $this->createSubject($config);
		
		$config->FileConsumer = new CallbackConsumer(function() use (&$isCalled) { $isCalled = true; });
		
		$subject->run();
		
		$this->assertTrue($isCalled);
	}
	
	public function test_sanity_CorrectRelativePathUsed() 
	{
		$isCalled = false;
		$config = $this->createConfigForDir('sanity');
		$config->RelativePath = realpath($this->getTestDirectoryPath('sanity') . '/..');
		$subject = $this->createSubject($config);
		
		$config->FileConsumer = new CallbackConsumer(function($file) use (&$calledFile) { $calledFile = $file; });
		
		$subject->run();
		
		$this->assertEquals('sanity/test', $calledFile);
	}
	
	
	
	public function test_run_EmptyDirectory_FileConsumerNeverCalled() 
	{
		$this->createEmptyDirectory();
		
		$config = $this->createConfigForDir('empty');
		$subject = $this->createSubject($config);
		
		$config->FileConsumer = $this->createFailConsumer();
		
		$subject->run();
	}
	
	public function test_run_EmptyDirectory_DirectoryConsumerNeverCalled() 
	{
		$this->createEmptyDirectory();
		
		$config = $this->createConfigForDir('empty');
		$subject = $this->createSubject($config);
		
		$config->DirConsumer = $this->createFailConsumer();
		
		$subject->run();
	}
	
	public function test_run_EmptyDirectory_FileFilterNeverCalled() 
	{
		$this->createEmptyDirectory();
		
		$config = $this->createConfigForDir('empty');
		$subject = $this->createSubject($config);
		
		// File filter not called if there is no file consumer
		$config->FileConsumer = new CallbackConsumer(function() {});
		$config->FileFilter = $this->createFailFilter();
		
		$subject->run();
	}
	
	public function test_run_EmptyDirectory_DirectoryFilterNeverCalled() 
	{
		$this->createEmptyDirectory();
		
		$config = $this->createConfigForDir('empty');
		$subject = $this->createSubject($config);
		
		$config->DirFilter = $this->createFailFilter();
		
		$subject->run();
	}
	
	
	public function test_run_FlatDirectory_FileConsumerCalledForEachFile() 
	{
		$result = [];
		
		$config = $this->createConfigForDir('flat');
		$subject = $this->createSubject($config);
		
		$config->FileConsumer = new CallbackConsumer(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['file_a', 'file_a1', 'file_b'], $result);
	}
	
	public function test_run_FlatDirectory_FileFilterCalledForEachFile() 
	{
		$result = [];
		
		$config = $this->createConfigForDir('flat');
		$subject = $this->createSubject($config);
		
		// File filter not called if there is no file consumer
		$config->FileConsumer = new CallbackConsumer(function() {});
		$config->FileFilter = new CallbackFilter(function($file) use (&$result) { $result[] =  $file; }); 
		
		$subject->run();
		
		$this->assertSameFileSet(['file_a', 'file_a1', 'file_b'], $result);
	}
	
	public function test_run_OnlyNotFilteredFilesPassedToConsumer()
	{
		$result = [];
		
		$config = $this->createConfigForDir('flat');
		$subject = $this->createSubject($config);
		
		$config->FileFilter = new CallbackFilter(function($file) { return $file != 'file_b'; });
		$config->FileConsumer = new CallbackConsumer(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['file_a', 'file_a1'], $result);
	}
	
	
	public function test_run_SubDirectoriesExist_SubFilesConsumer()
	{
		$result = [];
		
		$config = $this->createConfigForDir('subdir');
		$subject = $this->createSubject($config);
		
		$config->FileConsumer = new CallbackConsumer(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['subdir_a/file_a', 'subdir_b/file_b'], $result);
	}
	
	public function test_run_SubDirectoriesExist_SubFilesFiltered()
	{
		$result = [];
		
		$config = $this->createConfigForDir('subdir');
		$subject = $this->createSubject($config);
		
		// File filter not called if there is no file consumer
		$config->FileConsumer = new CallbackConsumer(function() {});
		$config->FileFilter = new CallbackFilter(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['subdir_a/file_a', 'subdir_b/file_b'], $result);
	}
	
	public function test_run_SubDirectoriesExist_SubDirectoriesFiltered()
	{
		$result = [];
		
		$config = $this->createConfigForDir('subdir');
		$subject = $this->createSubject($config);
		
		$config->DirFilter = new CallbackFilter(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['subdir_a', 'subdir_b'], $result);
	}
	
	public function test_run_SubDirectoryFiltered_DirFilesNotConsumed()
	{
		$result = [];
		
		$config = $this->createConfigForDir('subdir');
		$subject = $this->createSubject($config);
		
		$config->DirFilter = new CallbackFilter(function($dir) { return $dir != 'subdir_b'; });
		$config->FileConsumer = new CallbackConsumer(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['subdir_a/file_a'], $result);
	}
	
	
	public function test_run_SubDirectoriesExist_SubDirectoriesFilePathIsCorrect()
	{
		$result = [];
		
		$config = $this->createConfigForDir('subdir_level_2');
		$subject = $this->createSubject($config);
		
		$config->DirConsumer = new CallbackConsumer(function($file) use (&$result) { $result[] =  $file; });
		
		$subject->run();
		
		$this->assertSameFileSet(['subdir', 'subdir/inner_a', 'subdir/inner_b'], $result);
	}
	
}