<?php

namespace Livewire\Concerns;

use Illuminate\Support\Str;

trait ReceivesEvents
{
    public function syncInput($name, $value)
    {
        if (method_exists($this, 'onSync' . Str::studly($name))) {
            $this->{'onSync' . Str::studly($name)}($value);
        }

        $this->removeFromDirtyPropertiesList($name);

        $this->{$name} = $value;
    }
}
