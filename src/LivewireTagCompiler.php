<?php

namespace Livewire;

use Illuminate\Support\Str;
use Illuminate\View\Compilers\ComponentTagCompiler;

class LivewireTagCompiler extends ComponentTagCompiler
{
    public function compile($value)
    {
        return $this->compileLivewireSelfClosingTags($value);
    }

    protected function compileLivewireSelfClosingTags($value)
    {
        $pattern = "/
            <
                \s*
                livewire\:([\w\-\:\.]*)
                \s*
                (?<attributes>
                    (?:
                        \s+
                        [\w\-:.@]+
                        (
                            =
                            (?:
                                \\\"[^\\\"]*\\\"
                                |
                                \'[^\']+\'
                                |
                                [^\'\\\"=<>]+
                            )
                        )?
                    )*
                    \s*
                )
            \/?>
        /x";

        return preg_replace_callback($pattern, function (array $matches) {
            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            // Convert kebab attributes to camel-case.
            $attributes = collect($attributes)->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            })->toArray();

            if ($matches[1] === 'styles') return '@livewireStyles';
            if ($matches[1] === 'scripts') return '@livewireScripts';

            return $this->componentString($matches[1], $attributes);
        }, $value);
    }

    protected function componentString($component, array $attributes)
    {
        $component = cast_to_string($component);

        if (isset($attributes['key'])) {
            $key = $attributes['key'];
            unset($attributes['key']);

            return "@livewire('{$component}', [".$this->attributesToString($attributes)."], key({$key}))";
        }

        return "@livewire('{$component}', [".$this->attributesToString($attributes).'])';
    }
}
