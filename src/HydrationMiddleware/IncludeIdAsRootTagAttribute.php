<?php

namespace Livewire\HydrationMiddleware;

class IncludeIdAsRootTagAttribute implements HydrationMiddleware
{
    public static function hydrate($unHydratedInstance, $request)
    {
        //
    }

    public static function dehydrate($instance, $response)
    {
        $callable = new AddAttributesToRootTagOfHtml;

        $response->dom = $callable($response->dom, [
            'id' => $instance->id,
        ]);
    }
}
