<?php

namespace Dogado\Tests\Laroute\Unit\Routes;

use Dogado\Laroute\Exceptions\ZeroRoutesException;
use Dogado\Laroute\Routes\Collection;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class CollectionTest extends TestCase
{
    protected $routeCollection;

    protected $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routeCollection = m::mock(RouteCollection::class);
    }

    public function testFailOnEmptyRoutes()
    {
        $this->expectException(ZeroRoutesException::class);
        $this->routeCollection->shouldReceive('count')->once()->andReturn(0);
        new Collection($this->routeCollection, 'all');
    }

    public function testIFailedAtTestingACollection()
    {
        $route = m::mock(Route::class);
        $route->shouldReceive('domain')->once()->andReturn(null);
        $route->shouldReceive('getDomain')->once()->andReturn(null);
        $route->shouldReceive('methods')->twice()->andReturn(['GET']);
        $route->shouldReceive('uri')->twice()->andReturn('/');
        $route->shouldReceive('getName')->twice()->andReturn(0);
        $route->shouldReceive('getAction')->twice()->andReturn([]);

        $routeCollection = new RouteCollection();
        $routeCollection->add($route);

        $collection = new Collection($routeCollection, 'all');
        $entry = $collection->first();
        $this->assertArrayHasKey('host', $entry);
        $this->assertArrayHasKey('methods', $entry);
        $this->assertArrayHasKey('uri', $entry);
        $this->assertArrayHasKey('name', $entry);
    }

    public function tearDown(): void
    {
        m::close();
    }
}
