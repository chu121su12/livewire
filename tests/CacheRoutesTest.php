<?php

namespace Tests;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class CacheRoutesTest extends TestCase
{
    /** @test */
    public function livewire_routes_are_cacheable()
    {
        $routesWithClosure = collect(RouteFacade::getRoutes())->filter(function (Route $route) {
            return $route->getAction('uses') instanceof Closure;
        })->reject(function (Route $route) {
            return $route->uri === 'livewire-dusk/{}';
        });

        $this->assertTrue($routesWithClosure->isEmpty());
    }
}
