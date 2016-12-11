<?php
namespace Itarator;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property IConsumer|null	$FileConsumer
 * @property IConsumer|null	$DirConsumer
 * @property IFilter|null	$FileFilter
 * @property IFilter|null	$DirFilter
 * @property string			$RootDir
 * @property string			$RelativePath
 */
class Config extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'FileConsumer'	=> LiteSetup::createInstanceOf(IConsumer::class),
			'DirConsumer'	=> LiteSetup::createInstanceOf(IConsumer::class),
			'FileFilter'	=> LiteSetup::createInstanceOf(IFilter::class),
			'DirFilter'		=> LiteSetup::createInstanceOf(IFilter::class),
			'RootDir'		=> LiteSetup::createString(''),
			'RelativePath'	=> LiteSetup::createString(''),
		];
	}
	
	
	/**
	 * @param IFilter $filter
	 * @return static
	 */
	public function setFilter(IFilter $filter)
	{
		$this->DirFilter = $filter;
		$this->FileFilter = $filter;
		return $this;
	}

	/**
	 * @param IConsumer $consumer
	 * @return static
	 */
	public function setConsumer(IConsumer $consumer)
	{
		$this->DirConsumer = $consumer;
		$this->FileConsumer = $consumer;
		return $this;
	}
}