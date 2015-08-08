<?php


namespace Pinepain\SimpleConfig\Loaders;


class MergedFilesLoader implements LoaderInterface
{
    /**
     * @var FilesLoader
     */
    private $loader;

    /**
     * @var string
     */
    private $merge_file;

    public function __construct(FilesLoader $loader, $merge_file)
    {
        $this->loader     = $loader;
        $this->merge_file = $merge_file;

        // check whether PhpParser installed
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        if (!file_exists($this->merge_file)) {
            $files = $this->loader->getConfigFiles($this->loader->getDirectory());

            $this->generateMerge($this->merge_file, $files);
        }

        return require $this->merge_file;
    }

    /**
     * @param       $cache_file
     * @param array $source_files
     */
    public function generateMerge($cache_file, array $source_files)
    {
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);

        $config = new \PhpParser\Node\Expr\Array_();

        foreach ($source_files as $basename => $file) {
            $stmts = $parser->parse(file_get_contents($file));

            if (empty($stmts) || !($stmts[0] instanceof \PhpParser\Node\Stmt\Return_)) {
                throw new \RuntimeException("Unable to locate return statement with config items in {$file}");
            }

            $config->items[] = new \PhpParser\Node\Expr\ArrayItem(
                $stmts[0]->expr,
                new \PhpParser\Node\Scalar\String_($basename)
            );
        }

        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard();

        $out = $prettyPrinter->prettyPrintExpr($config);

        file_put_contents($cache_file,
            '<?php' . PHP_EOL . PHP_EOL
            . '// THIS IS AUTOGENERATED FILE! DO NOT EDIT IT!' . PHP_EOL . PHP_EOL
            . 'return ' . $out . ';' . PHP_EOL
        );
    }
}