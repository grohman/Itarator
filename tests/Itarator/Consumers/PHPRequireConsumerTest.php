<?php
namespace Itarator\Consumers;


class PHPRequireConsumerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return string
	 */
	private function generateFile()
	{
		foreach (glob(__DIR__ . '/PHPRequireConsumer/php_*') as $file) 
		{
			unlink($file);
		}
		
		$name = __DIR__ . '/PHPRequireConsumer/php_test_' . time() . '.php';
		file_put_contents($name, "<?php\n\$a = 1;");
		return $name;
	}
	
	
	public function test_fileRequired()
	{
		$consumer = new PHPRequireConsumer();
		$file = $this->generateFile();
		$includedBefore = get_included_files();
		
		$consumer->consume($file);
		
		$includedAfter = get_included_files();
		$this->assertEquals([$file], array_values(array_diff($includedAfter, $includedBefore)));
	}
}