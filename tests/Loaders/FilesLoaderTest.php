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
            '.ignore' => 'should be ignored',
            '.ignore.php' => 'should be ignored',
            'some_folder' => [
                'some_file.php' => 'should be ignored',
            ],
            'test.php' => '<?php return [];',
            'foo.php' => '<?php return ["bar"];',
            'bar.php' => '<?php return "baz";',
        ];

        $this->root = vfsStream::setup('root', null, $structure);
    }

    public function testLoad()
    {
        $loader = new FilesLoader();

        $expected = [
            'test' => [],
            'foo' => ['bar'],
            'bar' => 'baz',
        ];

        $this->assertSame($expected, $loader->load(vfsStream::url('root')));
    }
}
