<?php

namespace Dogado\Laroute;

use Illuminate\Support\ServiceProvider;
use Dogado\Laroute\Routes\Collection as RoutesCollection;
use Dogado\Laroute\Console\Commands\LarouteGeneratorCommand;
use Dogado\Laroute\Generators\GeneratorInterface;
use Dogado\Laroute\Generators\TemplateGenerator;
use Dogado\Laroute\Compilers\CompilerInterface;
use Dogado\Laroute\Compilers\TemplateCompiler;

class LarouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../config/laroute.php');
        $this->publishes([$source => config_path('laroute.php')]);
        $this->mergeConfigFrom($source, 'laroute');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGenerator();
        $this->registerCompiler();
        $this->registerCommand();
    }

    /**
     * Register the generator.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app->bind(GeneratorInterface::class, TemplateGenerator::class);
    }

    /**
     * Register the compiler.
     *
     * @return void
     */
    protected function registerCompiler()
    {
        $this->app->bind(CompilerInterface::class, TemplateCompiler::class);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCommand()
    {
        $this->app->singleton('command.laroute.generate', function ($app) {
            $config = $app['config'];
            $routes = new RoutesCollection(
                $app['router']->getRoutes(),
                $config->get('laroute.filter', 'all')
            );
            $generator = $app->make(GeneratorInterface::class);

            return new LarouteGeneratorCommand($config, $routes, $generator);
        });

        $this->commands('command.laroute.generate');
    }
}
