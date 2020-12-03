<?php

namespace Livewire;

use Illuminate\View\Component as IlluminateComponent;

class CreateBladeViewFromString extends IlluminateComponent
{
    public function __invoke($contents)
    {
        return $this->createBladeViewFromString(app('view'), $contents);
    }

    public function render()
    {
        //
    }
}
