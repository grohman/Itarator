<?php
namespace Itarator\Consumers;


use Itarator\IConsumer;


class ConsumerSet implements IConsumer
{
	/** @var IConsumer[] */
	private $set = [];
	
	
	/**
	 * @param IConsumer[]|callable[] $consumers
	 * @return static
	 */
	public function add(...$consumers)
	{
		foreach ($consumers as $consumer)
		{
			if (is_callable($consumer))
			{
				$this->set[] = new CallbackConsumer($consumer);
			}
			else if ($consumer instanceof IConsumer)
			{
				$this->set[] = $consumer;
			}
			else
			{
				throw new \Exception('Invalid parameter passed. Accepting callback or ' . IConsumer::class . 'only!');
			}
		}
		
		return $this;
	}

	/**
	 * @return IConsumer[]
	 */
	public function getSet()
	{
		return $this->set;
	}
	
	/**
	 * @param string $path
	 */
	public function consume($path)
	{
		foreach ($this->set as $consumer)
		{
			$consumer->consume($path);
		}
	}
}