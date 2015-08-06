<?php


namespace Pinepain\SimpleConfig\Tests;


use Pinepain\SimpleConfig\Config;
use Pinepain\SimpleConfig\ConfigInterface;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ConfigInterface */
    private $config;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->config = new Config(['foo' => 'bar', 'test' => ['nested' => 'value'], 'null' => null]);

        parent::setUp();
    }

    public function testAll()
    {
        $config = $this->config;

        $expected = ['foo' => 'bar', 'test' => ['nested' => 'value'], 'null' => null];

        $this->assertSame($expected, $config->all());
    }

    public function testHas()
    {
        $config = $this->config;

        $this->assertFalse($config->has(''));
        $this->assertFalse($config->has('.'));
        $this->assertFalse($config->has('..'));

        $this->assertTrue($config->has('foo'));
        $this->assertTrue($config->has('null'));
        $this->assertTrue($config->has('test'));
        $this->assertTrue($config->has('test.nested'));

        $this->assertFalse($config->has('nonexistent'));
        $this->assertFalse($config->has('test.nonexistent'));
        $this->assertFalse($config->has('test.nested.nonexistent'));
    }

    public function testGet()
    {
        $config = $this->config;

        $this->assertNull($config->get('nonexistent'));
        $this->assertEquals('default-value', $config->get('nonexistent', 'default-value'));

        $this->assertNull($config->get(''));
        $this->assertNull($config->get('.'));
        $this->assertNull($config->get('..'));

        $this->assertSame('bar', $config->get('foo'));
        $this->assertSame(['nested' => 'value'], $config->get('test'));
        $this->assertSame('value', $config->get('test.nested'));
        $this->assertSame(null, $config->get('null'));
        $this->assertSame(null, $config->get('null'));
    }

    public function testSet()
    {
        $config = $this->config;

        // set new value
        $this->assertFalse($config->has('new'));
        $config->set('new', 'new value');
        $this->assertTrue($config->has('new'));
        $this->assertSame('new value', $config->get('new'));

        // set new nested value
        $this->assertFalse($config->has('new-nested.nested'));
        $config->set('new-nested.nested', 'new value');
        $this->assertTrue($config->has('new-nested.nested'));
        $this->assertSame('new value', $config->get('new-nested.nested'));

        // set new value on top of scalar
        $this->assertFalse($config->has('new.nested'));
        $config->set('new.nested', 'new value');
        $this->assertTrue($config->has('new.nested'));
        $this->assertSame('new value', $config->get('new.nested'));

        $this->assertSame(['nested' => 'new value'], $config->get('new'));
    }

}
