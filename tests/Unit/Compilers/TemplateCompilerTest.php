<?php

namespace Dogado\Tests\Laroute\Unit\Compilers;

use Dogado\Laroute\Compilers\TemplateCompiler;
use Dogado\Laroute\Compilers\CompilerInterface;
use Orchestra\Testbench\TestCase;

class TemplateCompilerTest extends TestCase
{
    protected $compiler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compiler = new TemplateCompiler();
    }

    public function testItIsOfTheCorrectInterface()
    {
        $this->assertInstanceOf(CompilerInterface::class, $this->compiler);
    }

    public function testItCanCompileAString()
    {
        $template = 'Hello $YOU$, my name is $ME$.';
        $data = ['you' => 'Stranger', 'me' => 'Aaron'];
        $expected = 'Hello Stranger, my name is Aaron.';

        $this->assertSame($expected, $this->compiler->compile($template, $data));
    }
}
