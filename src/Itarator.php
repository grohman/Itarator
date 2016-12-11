<?php
use Itarator\Config;
use Itarator\IFilter;
use Itarator\IConsumer;
use Itarator\IteratorFactory;
use Itarator\RecursiveIterator;
use Itarator\Filters\CallbackFilter;
use Itarator\Filters\NameRegexFilter;
use Itarator\Consumers\CallbackConsumer;


class Itarator
{
	/** @var Config */
	private $config;
	
	/** @var IteratorFactory */
	private $factory;


	/**
	 * @param string $filter
	 * @return NameRegexFilter
	 */
	private function createFilterForWildCard($filter)
	{
		$filter = preg_quote($filter);
		$filter = '/' . str_replace(['\*'], ['.*'], $filter) . '/';
		return new NameRegexFilter($filter);
	}
	
	
	public function __construct()
	{
		$this->config = new Config();
		$this->factory = new IteratorFactory();
	}


	/**
	 * Should be used only for unit tests.
	 * @param IteratorFactory $factory
	 */
	public function setFactory(IteratorFactory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @param IConsumer $consumer
	 * @return static
	 */
	public function setConsumer(IConsumer $consumer)
	{
		$this->config->setConsumer($consumer);
		return $this;
	}
	
	/**
	 * @param IConsumer $consumer
	 * @return static
	 */
	public function setFileConsumer(IConsumer $consumer)
	{
		$this->config->FileConsumer = $consumer;
		return $this;
	}
	
	/**
	 * @param IConsumer $consumer
	 * @return static
	 */
	public function setDirectoryConsumer(IConsumer $consumer)
	{
		$this->config->DirConsumer = $consumer;
		return $this;
	}

	/**
	 * @param IFilter|string $filter If string, treated as a wild card expression on the file name.
	 * @return static
	 */
	public function setFilter($filter)
	{
		if (is_string($filter))
		{
			$this->config->setFilter($this->createFilterForWildCard($filter));
		}
		else 
		{
			$this->config->setFilter($filter);
		}
		
		return $this;
	}
	
	/**
	 * @param IFilter|string $filter If string, treated as a wild card expression on the file name.
	 * @return static
	 */
	public function setDirectoryFilter($filter)
	{
		$this->config->DirFilter = is_string($filter) ?
			$this->createFilterForWildCard($filter) :
			$filter;
		
		return $this;
	}
	
	/**
	 * @param IFilter|string $filter If string, treated as a wild card expression on the file name.
	 * @return static
	 */
	public function setFileFilter($filter)
	{
		$this->config->FileFilter = is_string($filter) ?
			$this->createFilterForWildCard($filter) :
			$filter;
		
		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackFilter($callback)
	{
		$this->config->setFilter(new CallbackFilter($callback));
		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackFilesFilter($callback)
	{
		$this->config->FileFilter = new CallbackFilter($callback);
		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackDirectoriesFilter($callback)
	{
		$this->config->DirFilter = new CallbackFilter($callback);
		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackConsumer($callback)
	{
		$this->config->setConsumer(new CallbackConsumer($callback));
		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackFilesConsumer($callback)
	{
		$this->config->FileConsumer = new CallbackConsumer($callback);
		return $this;
	}
	
	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallbackDirectoriesConsumer($callback)
	{
		$this->config->DirConsumer = new CallbackConsumer($callback);
		return $this;
	}

	/**
	 * @param string $root
	 * @return static
	 */
	public function setRootDirectory($root)
	{
		$path = realpath($root);
		
		if ($path === false)
			throw new \Exception('The path ' . $root . ' is invalid!');
		
		$this->config->RootDir = $path;
		$this->config->RelativePath = $path;
		return $this;
	}

	/**
	 * @param string $relative
	 * @return static
	 */
	public function setRelativeDirectory($relative)
	{
		$path = realpath($relative);
		
		if ($path === false)
			throw new \Exception('The path ' . $path . ' is invalid!');
			
		
		if (!$this->config->RootDir)
			throw new \Exception('Root path must be set before relative path is set!');
		
		if (strpos($this->config->RootDir, $path) !== 0)
			throw new \Exception('Root directory ' . $this->config->RootDir . 
				' must be a sub directory of ' . $path . '!');
		
		$this->config->RelativePath = $path;
		
		return $this;
	}
	
	/**
	 * @return RecursiveIterator
	 */
	public function get()
	{
		$iterator = $this->factory->get();
		$iterator->setConfig($this->config);
		return $iterator;
	}

	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}
	
	
	/**
	 * @return int Number of consumed items.
	 */
	public function execute()
	{
		
	}
}