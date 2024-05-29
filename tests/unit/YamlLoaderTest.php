<?php

use MattFerris\Configuration\Loaders\YamlLoader;
use MattFerris\Configuration\Resources\FileResourceInterface;
use org\bovigo\vfs\vfsStream;

class YamlLoaderTest extends PHPUnit\Framework\TestCase
{
    public function testLoad()
    {
        vfsStream::setup('root');
        $path = vfsStream::url('root').'/foo.yaml';
        file_put_contents($path, 'foo: bar');

        $resource = $this->createMock(FileResourceInterface::class);
        $resource->expects($this->once())
            ->method('getPath')
            ->willReturn($path);

        $loader = new YamlLoader();
        $result = $loader->load($resource);

        $this->assertEquals($result, ['foo' => 'bar']);
    }
}

