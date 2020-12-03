<?php

namespace Livewire;

use Illuminate\Support\Str;

class Livewire_str_callable {
            public function __call($method, $params) {
                return Str::$method(...$params);
            }
        };

if (! function_exists('Livewire\str')) {
    function str($string = null)
    {
        if (is_null($string)) return new Livewire_str_callable;

        return Str::of($string);
    }
}
