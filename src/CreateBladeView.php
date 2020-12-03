<?php

namespace Livewire;

use Illuminate\View\Component as ViewComponent;

class CreateBladeView extends ViewComponent
{
    public static function fromString($contents)
    {
        return (new static)->createBladeViewFromString(app('view'), $contents);
    }

    public function render() {}
}
