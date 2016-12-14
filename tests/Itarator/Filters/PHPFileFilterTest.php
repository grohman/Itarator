<?php
namespace Itarator\Filters;


class PHPFileFilterTest extends \PHPUnit_Framework_TestCase
{
	public function test_NotPhpFile_ReturnFalse()
	{
		$this->assertFalse((new PHPFileFilter())->filter('abc.txt'));
	}
	
	public function test_ValidFile_ReturnTrue()
	{
		$this->assertTrue((new PHPFileFilter())->filter('abc.php'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.phtml'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.php3'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.php4'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.php5'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.php7'));
		$this->assertTrue((new PHPFileFilter())->filter('abc.phps'));
	}
	
	public function test_ValidFileWithNotLowerCaseChars_ReturnTrue()
	{
		$this->assertTrue((new PHPFileFilter())->filter('abc.pHp'));
	}
	
	public function test_InvalidExtensionAfterAValidOne_ReturnFalse()
	{
		$this->assertFalse((new PHPFileFilter())->filter('abc.php.not'));
	}
}