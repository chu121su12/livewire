<?php

namespace Livewire\HydrationMiddleware;

class ForwardPrefetch implements HydrationMiddleware
{
    public static $prefetchCache;

    public static function hydrate($unHydratedInstance, $request)
    {
        static::$prefetchCache = isset($request['fromPrefetch']) ? $request['fromPrefetch'] : false;
    }

    public static function dehydrate($instance, $response)
    {
        $response['fromPrefetch'] = static::$prefetchCache;
    }
}
