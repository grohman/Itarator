<?php
namespace Itarator;


class RecursiveIterator
{
	private $rootDir;
	
	/** @var IFilter */
	private $dirFilter = null;
	
	/** @var IFilter */
	private $fileFilter = null;
	
	/** @var IConsumer */
	private $dirConsumer = null;
	
	/** @var IConsumer */
	private $fileConsumer = null;


	/**
	 * @param string $dir
	 * @param IFilter $filter
	 * @param IConsumer $consumer
	 */
	private function consume($dir, IFilter $filter = null, IConsumer $consumer = null)
	{
		if (!$consumer || !$filter) 
			return;
		
		if (!$filter->filter($dir))
			return;
		
		$consumer->consume($dir);
	}

	/**
	 * @param string $dir
	 */
	private function consumeDir($dir)
	{
		$this->consume($dir, $this->dirFilter, $this->dirConsumer);
	}

	/**
	 * @param string $file
	 */
	private function consumeFile($file)
	{
		$this->consume($file, $this->fileFilter, $this->fileConsumer);
	}
	
	
	
	

	/**
	 * @param string $rootPath
	 */
	public function __construct($rootPath)
	{
	}


	/**
	 * @param IFilter|null $filter
	 * @return static
	 */
	public function setDirFilter(IFilter $filter = null)
	{
		
	}

	/**
	 * @param IFilter|null $filter
	 * @return static
	 */
	public function setFileFilter(IFilter $filter = null)
	{
		
	}

	/**
	 * @param IConsumer|null $consumer
	 * @return static
	 */
	public function setDirConsumer(IConsumer $consumer = null)
	{
		
	}

	/**
	 * @param IConsumer|null $consumer
	 * @return static
	 */
	public function setFileConsumer(IConsumer $consumer = null)
	{
		
	}
	
	
	public function run($fileFilter = '*')
	{
		
	}
}