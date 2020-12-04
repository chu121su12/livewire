<?php

namespace Livewire\Patch;

use Illuminate\Support\Str;

class StrCallable
{
    public function __call($method, $params) {
        return Str::$method(...$params);
    }
}
