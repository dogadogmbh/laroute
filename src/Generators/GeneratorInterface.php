<?php

namespace Dogado\Laroute\Generators;

use Dogado\Laroute\Compilers\CompilerInterface;
use Illuminate\Filesystem\Filesystem;

interface GeneratorInterface
{
    /**
     * Create a new template generator instance.
     *
     * @param $compiler   \Dogado\Laroute\Compilers\CompilerInterface
     * @param $filesystem \Illuminate\Filesystem\Filesystem
     */
    public function __construct(CompilerInterface $compiler, Filesystem $filesystem);

    /**
     * Compile the template.
     *
     * @param $templatePath
     * @param $templateData
     * @param $filePath
     *
     * @return string
     */
    public function compile($templatePath, array $templateData, $filePath);
}
