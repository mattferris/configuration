<?php

use MattFerris\Configuration\Locators\FileLocator;
use MattFerris\Configuration\Resources\FileResource;
use org\bovigo\vfs\vfsStream;

class FileLocatorTest extends PHPUnit_Framework_TestCase
{
    public function testLocate()
    {
        vfsStream::setup('root');
        $base = vfsStream::url('root');

        mkdir("$base/A");
        mkdir("$base/B");

        file_put_contents("$base/B/foo.php", 'foo');

        $locator = new FileLocator(["$base/A", "$base/B"]);
        $foo = $locator->locate('foo.php');
        $bar = $locator->locate('bar.php');

        $this->assertInstanceOf(FileResource::class, $foo);
        $this->assertEquals($foo->getPath(), "$base/B/foo.php");
        $this->assertFalse($bar);
    }

    /**
     * @depends testLocate
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage resource must be a non-empty string
     */
    public function testLocateWithNonStringResource()
    {
        $locator = new FileLocator([vfsStream::url('root')]);
        $locator->locate([]);
    }

    /**
     * @depends testLocate
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage resource must be a non-empty string
     */
    public function testLocateWithEmtpyStringResource()
    {
        $locator = new FileLocator([vfsStream::url('root')]);
        $locator->locate('');
    }
}

