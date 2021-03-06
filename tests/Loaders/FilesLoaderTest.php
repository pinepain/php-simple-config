<?php


namespace Pinepain\SimpleConfig\Tests\Loaders;

use org\bovigo\vfs\vfsStream;
use Pinepain\SimpleConfig\Loaders\FilesLoader;

class FilesLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $root;


    protected function setUp()
    {
        parent::setUp();

        $structure = [
            '.ignore'     => 'should be ignored',
            '.ignore.php' => 'should be ignored',
            'some_folder' => [
                'some_file.php' => 'should be ignored',
            ],
            'test.php'    => '<?php return [];',
            'foo.php'     => '<?php return ["bar"];',
            'bar.php'     => '<?php return "baz";',
        ];

        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testGetDirectory()
    {
        $loader = new FilesLoader('test-directory');

        $this->assertSame('test-directory', $loader->getDirectory());
    }

    public function testGetConfigFiles()
    {
        $loader = new FilesLoader('config directory path not in use in this case');

        $expected = [
            'test' => 'vfs://root/test.php',
            'foo'  => 'vfs://root/foo.php',
            'bar'  => 'vfs://root/bar.php',
        ];
        $this->assertSame($expected, $loader->getConfigFiles(vfsStream::url('root')));
    }

    public function testLoad()
    {
        /** @var FilesLoader | \PHPUnit_Framework_MockObject_MockObject $loader */

        $loader = $this->getMockBuilder('\Pinepain\SimpleConfig\Loaders\FilesLoader')
            ->setMethods(['getConfigFiles'])
            ->setConstructorArgs(['config directory path not in use in this case'])
            ->getMock();

        $loader->expects($this->once())
            ->method('getConfigFiles')
            ->willReturn(
                [
                    'test' => 'vfs://root/test.php',
                    'foo'  => 'vfs://root/foo.php',
                    'bar'  => 'vfs://root/bar.php',
                ]
            );

        $expected = [
            'test' => [],
            'foo'  => ['bar'],
            'bar'  => 'baz',
        ];

        $this->assertSame($expected, $loader->load());
    }
}
