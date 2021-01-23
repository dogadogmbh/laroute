<?php

namespace Dogado\Laroute\Routes;

use Dogado\Laroute\Exceptions\ZeroRoutesException;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection
{
    public function __construct(RouteCollection $routes, string $filter)
    {
        $this->items = $this->parseRoutes($routes, $filter);
    }

    /**
     * Parse the routes into a jsonable output.
     *
     * @param RouteCollection $routes
     * @param string $filter
     *
     * @return array
     * @throws ZeroRoutesException
     */
    protected function parseRoutes(RouteCollection $routes, string $filter)
    {
        $this->guardAgainstZeroRoutes($routes);

        $results = [];

        foreach ($routes as $route) {
            $results[] = $this->getRouteInformation($route, $filter);
        }

        return array_values(array_filter($results));
    }

    /**
     * Throw an exception if there aren't any routes to process.
     *
     * @param RouteCollection $routes
     *
     * @throws ZeroRoutesException
     */
    protected function guardAgainstZeroRoutes(RouteCollection $routes)
    {
        if ($routes->count() < 1) {
            throw new ZeroRoutesException('No routes have been defined yet.');
        }
    }

    /**
     * Get the route information for a given route.
     *
     * @param $route \Illuminate\Routing\Route
     * @param $filter string
     *
     * @return array
     */
    protected function getRouteInformation(Route $route, string $filter)
    {
        $host = $route->domain();
        $methods = $route->methods();
        $uri = $route->uri();
        $name = $route->getName();
        $laroute = Arr::get($route->getAction(), 'laroute');

        switch ($filter) {
            case 'all':
                if ($laroute === false) {
                    return;
                }
                break;
            case 'only':
                if ($laroute !== true) {
                    return;
                }
                break;
        }

        return compact('host', 'methods', 'uri', 'name');
    }
}
