<?php

namespace Tests;

use Livewire\Commands\ComponentParser;

class FileManipulationCommandParserTest extends TestCase
{
    /**
     * @test
     * @dataProvider classPathProvider
     */
    public function something($input, $component, $namespace, $classPath, $viewName, $viewPath)
    {
        $parser = new ComponentParser(
            'App\Http\Livewire',
            resource_path('views/livewire'),
            $input
        );

        $this->assertEquals($component, $parser->component());
        $this->assertEquals($namespace, $parser->classNamespace());
        $this->assertEquals(app_path($classPath), $parser->classPath());
        $this->assertEquals($viewName, $parser->viewName());
        $this->assertEquals(resource_path('views/'.$viewPath), $parser->viewPath());
    }

    public function classPathProvider()
    {
        if (windows_os())
        {
            return [
                [
                    'foo',
                    'foo',
                    'App\Http\Livewire',
                    '/Http\Livewire\Foo.php',
                    'livewire.foo',
                    '/livewire\foo.blade.php',
                ],
                [
                    'foo.bar',
                    'bar',
                    'App\Http\Livewire\Foo',
                    '/Http\Livewire\Foo\Bar.php',
                    'livewire.foo.bar',
                    '/livewire\foo\bar.blade.php',
                ],
                [
                    'foo.bar',
                    'bar',
                    'App\Http\Livewire\Foo',
                    '/Http\Livewire\Foo\Bar.php',
                    'livewire.foo.bar',
                    '/livewire\foo\bar.blade.php',
                ],
                [
                    'foo.bar',
                    'bar',
                    'App\Http\Livewire\Foo',
                    '/Http\Livewire\Foo\Bar.php',
                    'livewire.foo.bar',
                    '/livewire\foo\bar.blade.php',
                ],
                [
                    'foo-bar',
                    'foo-bar',
                    'App\Http\Livewire',
                    '/Http\Livewire\FooBar.php',
                    'livewire.foo-bar',
                    '/livewire\foo-bar.blade.php',
                ],
                [
                    'foo-bar.foo-bar',
                    'foo-bar',
                    'App\Http\Livewire\FooBar',
                    '/Http\Livewire\FooBar\FooBar.php',
                    'livewire.foo-bar.foo-bar',
                    '/livewire\foo-bar\foo-bar.blade.php',
                ],
            ];
        }

        return [
            [
                'foo',
                'foo',
                'App\Http\Livewire',
                'Http/Livewire/Foo.php',
                'livewire.foo',
                'livewire/foo.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Livewire\Foo',
                'Http/Livewire/Foo/Bar.php',
                'livewire.foo.bar',
                'livewire/foo/bar.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Livewire\Foo',
                'Http/Livewire/Foo/Bar.php',
                'livewire.foo.bar',
                'livewire/foo/bar.blade.php',
            ],
            [
                'foo.bar',
                'bar',
                'App\Http\Livewire\Foo',
                'Http/Livewire/Foo/Bar.php',
                'livewire.foo.bar',
                'livewire/foo/bar.blade.php',
            ],
            [
                'foo-bar',
                'foo-bar',
                'App\Http\Livewire',
                'Http/Livewire/FooBar.php',
                'livewire.foo-bar',
                'livewire/foo-bar.blade.php',
            ],
            [
                'foo-bar.foo-bar',
                'foo-bar',
                'App\Http\Livewire\FooBar',
                'Http/Livewire/FooBar/FooBar.php',
                'livewire.foo-bar.foo-bar',
                'livewire/foo-bar/foo-bar.blade.php',
            ],
            [
                'FooBar',
                'foo-bar',
                'App\Http\Livewire',
                'Http/Livewire/FooBar.php',
                'livewire.foo-bar',
                'livewire/foo-bar.blade.php',
            ],
        ];
    }
}
