<?php


namespace Pinepain\SimpleConfig\Tests;


use Pinepain\SimpleConfig\ImmutableConfig;


class ImmutableConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $config = $this->getMockBuilder('\Pinepain\SimpleConfig\ConfigInterface')
                       ->setMethods(['all'])
                       ->getMockForAbstractClass();

        $config->expects($this->once())
               ->method('all')
               ->willReturn(['test' => 'all']);

        $immutable = new ImmutableConfig($config);

        $this->assertSame(['test' => 'all'], $immutable->all());
    }


    public function testHas()
    {
        $config = $this->getMockBuilder('\Pinepain\SimpleConfig\ConfigInterface')
                       ->setMethods(['has'])
                       ->getMockForAbstractClass();

        $config->expects($this->exactly(2))
               ->method('has')
               ->withConsecutive(['exists'], ['missed'])
               ->willReturnOnConsecutiveCalls(true, false);

        $immutable = new ImmutableConfig($config);

        $this->assertTrue($immutable->has('exists'));
        $this->assertFalse($immutable->has('missed'));
    }

    public function testGet()
    {
        $config = $this->getMockBuilder('\Pinepain\SimpleConfig\ConfigInterface')
                       ->setMethods(['get'])
                       ->getMockForAbstractClass();

        $config->expects($this->exactly(3))
               ->method('get')
               ->withConsecutive(['exists'], ['missed'], ['missed', 'default'])
               ->willReturnOnConsecutiveCalls('existent', null, 'default');

        $immutable = new ImmutableConfig($config);

        $this->assertSame('existent', $immutable->get('exists'));
        $this->assertSame(null, $immutable->get('missed'));
        $this->assertSame('default', $immutable->get('missed', 'default'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Can't set key 'key' on immutable config
     */
    public function testSet()
    {
        $config = $this->getMockBuilder('\Pinepain\SimpleConfig\ConfigInterface')
                       ->getMockForAbstractClass();

        $immutable = new ImmutableConfig($config);

        $immutable->set('key', 'value');
    }

}
