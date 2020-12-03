<?php

namespace Livewire\Macros;

class RouterMacros
{
    public function layout()
    {
        return function ($layout) {
            return (new RouteRegistrarWithAllowedAttributes($this))
                ->allowAttributes('layout', 'section')
                ->layout($layout);
        };
    }

    public function section()
    {
        return function ($section) {
            return (new RouteRegistrarWithAllowedAttributes($this))
                ->allowAttributes('layout', 'section')
                ->section($section);
        };
    }

    public function livewire()
    {
        return function ($uri, $component) {
            return $this->get($uri, function () use ($component) {
                $componentClass = app('livewire')->getComponentClass($component);
                $reflected = new \ReflectionClass($componentClass);

                $currentLayout = $this->current()->getAction('layout');
                $currentSection = $this->current()->getAction('section');

                return app('view')->file(__DIR__.'/livewire-view.blade.php', [
                    'layout' => isset($currentLayout) ? $currentLayout : 'layouts.app',
                    'section' => isset($currentSection) ? $currentSection : 'content',
                    'component' => $component,
                    'componentOptions' => $reflected->hasMethod('mount')
                        ? (new PretendClassMethodIsControllerMethod($reflected->getMethod('mount'), $this))->retrieveBindings()
                        : [],
                ]);
            });
        };
    }
}
