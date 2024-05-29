<?php

use MattFerris\Configuration\Loaders\JsonLoader;
use MattFerris\Configuration\Resources\FileResourceInterface;
use org\bovigo\vfs\vfsStream;

class JsonLoaderTest extends PHPUnit\Framework\TestCase
{
    public function testLoad()
    {
        vfsStream::setup('root');
        $path = vfsStream::url('root').'/foo.php';
        file_put_contents($path, '{"foo": "bar"}');

        $resource = $this->createMock(FileResourceInterface::class);
        $resource->expects($this->once())
            ->method('getPath')
            ->willReturn($path);

        $loader = new JsonLoader();
        $result = $loader->load($resource);

        $this->assertEquals($result, ['foo' => 'bar']);
    }
}

