<?php

use MattFerris\Configuration\Resources\FileResource;
use org\bovigo\vfs\vfsStream;

class FileResourceTest extends PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        vfsStream::setup('root');
        $path = vfsStream::url('root').'/foo.php';
        file_put_contents($path, 'foo');
        $file = new FileResource($path);
        $this->assertEquals($file->getPath(), $path);
    }

    /**
     * @depends testConstruct
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage path "vfs://root/bar.php" does not exist
     */
    public function testConstructWithNonExistentPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('path "vfs://root/bar.php" does not exist');

        $file = new FileResource(vfsStream::url('root').'/bar.php');
    }
}

