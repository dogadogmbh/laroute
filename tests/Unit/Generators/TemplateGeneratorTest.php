<?php

namespace Dogado\Tests\Laroute\Unit\Generators;

use Dogado\Laroute\Compilers\TemplateCompiler;
use Dogado\Laroute\Generators\GeneratorInterface;
use Dogado\Laroute\Generators\TemplateGenerator;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class TemplateGeneratorTest extends TestCase
{
    protected $compiler;

    protected $filesystem;

    protected $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiler = m::mock(TemplateCompiler::class);
        $this->filesystem = m::mock(Filesystem::class);

        $this->generator = new TemplateGenerator($this->compiler, $this->filesystem);
    }

    public function testItIsOfTheCorrectInterface()
    {
        $this->assertInstanceOf(GeneratorInterface::class, $this->generator);
    }

    public function testItWillCompileAndSaveATemplate()
    {
        $template = 'Template';
        $templatePath = '/templatePath';
        $templateData = ['foo', 'bar'];
        $filePath = '/filePath';

        $this->filesystem
            ->shouldReceive('get')
            ->once()
            ->with($templatePath)
            ->andReturn($template);

        $this->filesystem
            ->shouldReceive('isDirectory')
            ->once()
            ->andReturn(true);

        $this->compiler
            ->shouldReceive('compile')
            ->once()
            ->with($template, $templateData)
            ->andReturn($template);

        $this->filesystem
            ->shouldReceive('put')
            ->once()
            ->with($filePath, $template);

        $actual = $this->generator->compile($templatePath, $templateData, $filePath);
        $this->assertSame($actual, $filePath);
    }

    public function tearDown(): void
    {
        m::close();
    }
}
