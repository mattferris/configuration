<?php

use MattFerris\Configuration\Configuration;
use MattFerris\Configuration\ConfigurationInterface;
use MattFerris\Configuration\LocatorInterface;
use MattFerris\Configuration\ResourceInterface;
use MattFerris\Configuration\LoaderInterface;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function makeResource()
    {
        return $this->createMock(ResourceInterface::class);
    }

    public function makeLocator($filenames, $resource = null, $expects = 1)
    {
        if (is_null($resource)) {
            $resource = $this->makeResource();
        }
        $locator = $this->createMock(LocatorInterface::class);
        $locator->expects($this->exactly($expects))
            ->method('locate')
            ->with($filenames)
            ->willReturn($resource);
        return $locator;
    }

    public function makeLoader($data, $resource = null, $expects = 1)
    {
        if ($resource === null) {
            $resource = $this->isInstanceOf(ResourceInterface::class);
        }

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->exactly($expects))
            ->method('load')
            ->with($resource)
            ->willReturn($data);
        return $loader;
    }

    public function testLoad()
    {
        $values = ['foo' => 'bar', 'this' => ['that' => 'test']];

        $locator = $this->makeLocator('foo.php');
        $loader = $this->makeLoader($values);

        $config = new Configuration($locator, $loader);

        $this->assertInstanceOf(Configuration::class, $config->load('foo.php'));
        $this->assertEquals($config->get(), $values);
        $this->assertTrue($config->has('foo'));
        $this->assertEquals($config->get('foo'), 'bar');
        $this->assertTrue($config->has('this.that'));
        $this->assertEquals($config->get('this.that'), 'test');
    }

    /**
     * @depends testLoad
     */
    public function testLoadWithMultipleResources()
    {
        $locator = $this->createMock(LocatorInterface::class);
        $locator->expects($this->exactly(2))
            ->method('locate')
            ->withConsecutive(['foo.php'], ['bar.php'])
            ->will($this->onConsecutiveCalls(false, $this->makeResource()));

        $loader = $this->makeLoader(['foo' => 'bar']);

        $config = new Configuration($locator, $loader);
        $config->load(['foo.php', 'bar.php']);

        $this->assertEquals($config->get(), ['foo' => 'bar']);
    }

    /**
     * @depends testLoad
     */
    public function testLoadWithKey()
    {
        $locator = $this->createMock(LocatorInterface::class);
        $locator->expects($this->exactly(2))
            ->method('locate')
            ->withConsecutive(['foo.php'], ['bar.php'])
            ->willReturn($this->makeResource());

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->exactly(2))
            ->method('load')
            ->with($this->isInstanceOf(ResourceInterface::class))
            ->will($this->onConsecutiveCalls(
                ['foo' => 'bar'],
                ['bar' => 'baz']
            ));

        $config = new Configuration($locator, $loader);
        $config->load('foo.php')->load('bar.php', 'foo');

        $this->assertEquals($config->get('foo.bar'), 'baz');
    }

    /**
     * @depends testLoadWithKey
     * @expectedException MattFerris\Configuration\KeyDoesNotExistException
     * @expectedExceptionMessage key "foo" does not exist
     */
    public function testLoadWithNonExistentKey()
    {
        $locator = $this->makeLocator('foo.php');
        $loader = $this->makeLoader([]);
        $config = new Configuration($locator, $loader);
        $config->load('foo.php', 'foo');
    }

    /**
     * @depends testLoad
     * @expectedException MattFerris\Configuration\KeyDoesNotExistException
     * @expectedExceptionMessage key "foo" does not exist
     */
    public function testGetWithNonExistentKey()
    {
        $locator = $this->createMock(LocatorInterface::class);
        $loader = $this->createMock(LoaderInterface::class);
        $config = new Configuration($locator, $loader);

        // test simple key
        $config->get('foo');
    }


    /**
     * @depends testLoad
     * @expectedException MattFerris\Configuration\KeyDoesNotExistException
     * @expectedExceptionMessage key "foo.bar" does not exist
     */
    public function testGetWithNonExistentCompoundKey()
    {
        $locator = $this->createMock(LocatorInterface::class);
        $loader = $this->createMock(LoaderInterface::class);
        $config = new Configuration($locator, $loader);

        // test compound key
        $config->get('foo.bar');
    }

    public function testGetLocator()
    {
        $locator = $this->createMock(LocatorInterface::class);
        $loader = $this->createMock(LoaderInterface::class);
        $config = new Configuration($locator, $loader);
        $this->assertEquals($config->getLocator(), $locator);
    }

    public function testImport()
    {
        $values = ['foo' => 'bar'];
        $importer = $this->createMock(ConfigurationInterface::class);
        $importer->expects($this->once())
            ->method('get')
            ->willReturn($values);

        $locator = $this->createMock(LocatorInterface::class);
        $loader = $this->createMock(LoaderInterface::class);

        $config = new Configuration($locator, $loader);

        $this->assertInstanceOf(Configuration::class, $config->import($importer));
        $this->assertEquals($config->get(), $values);
    }

    /**
     * @depends testImport
     */
    public function testImportWithKey()
    {
        $locator = $this->makeLocator('foo.php');
        $loader = $this->makeLoader(['foo' => 'bar']);

        $importer = $this->createMock(ConfigurationInterface::class);
        $importer->expects($this->once())
            ->method('get')
            ->willReturn(['baz' => 'bif']);

        $config = new Configuration($locator, $loader);
        $config->load('foo.php')->import($importer, 'foo');

        $this->assertEquals($config->get('foo.baz'), 'bif');
    }
}

