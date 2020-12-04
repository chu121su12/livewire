<?php

namespace Livewire;

use Illuminate\Support\Str;

if (! function_exists('Livewire\str')) {
    function str($string = null)
    {
        if (is_null($string)) return new Patch\StrCallable;

        return Str::of($string);
    }
}
