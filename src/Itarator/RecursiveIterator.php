<?php
namespace Itarator;


class RecursiveIterator
{
	/** @var Config */
	private $config;


	/**
	 * @param string $item
	 * @return string
	 */
	private function getRelativePath($item)
	{
		if ($this->config->RelativePath == '/')
			return $item;
		
		$length = strlen($this->config->RelativePath);
		
		// Remove the last '/' character
		return substr($item, $length + 1);
	}
	
	/**
	 * @param string $dir
	 */
	private function consumeDir($dir)
	{
		$relativePath = $this->getRelativePath($dir);
		
		if ($this->config->DirFilter && !$this->config->DirFilter->filter($relativePath))
		{
			return;
		}
		
		if ($this->config->DirConsumer)
		{
			$this->config->DirConsumer->consume($relativePath);
		}
		
		$this->iterateDir($dir);
	}

	/**
	 * @param string $file
	 */
	private function consumeFile($file)
	{
		if (!$this->config->FileConsumer)
			return;
		
		$relativePath = $this->getRelativePath($file);
		
		if ($this->config->FileFilter && !$this->config->FileFilter->filter($relativePath))
			return;
		
		$this->config->FileConsumer->consume($relativePath);
	}

	/**
	 * @param string $dir
	 */
	private function iterateDir($dir)
	{
		foreach (glob($dir . '/*') as $item)
		{
			if (is_dir($item))
			{
				$this->consumeDir($item);
			}
			else
			{
				$this->consumeFile($item);
			}
		}
	}
	

	/**
	 * @param Config $config
	 */
	public function setConfig(Config $config)
	{
		$this->config = $config;
	}
	
	
	public function run()
	{
		$this->iterateDir($this->config->RootDir);
	}
}