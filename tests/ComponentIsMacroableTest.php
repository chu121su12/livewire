<?php

namespace Tests;

use Livewire\Component;
use Livewire\Livewire;

class ComponentIsMacroable extends TestCase
{
    /** @test */
    public function it_resolves_the_mount_parameters()
    {
        $this->markTestSkipped('TODO');

        Component::macro('macroedMethod', function ($first, $second) {
            return [$first, $second];
        });

        Livewire::test(ComponentWithMacroedMethodStub::class)
            ->assertSet('foo', ['one', 'two']);
    }
}

class ComponentWithMacroedMethodStub extends Component
{
    public $foo;

    public function mount()
    {
        $this->foo = $this->macroedMethod('one', 'two');
    }

    public function render()
    {
        return view('null-view');
    }
}
