<?php

namespace Livewire;

use Exception;
use ReflectionClass;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Str;

class LivewireComponentsFinder
{
    protected $path;
    protected $files;
    protected $manifest;
    protected $manifestPath;

    public function __construct(Filesystem $files, $manifestPath, $path)
    {
        $this->files = $files;
        $this->path = $path;
        $this->manifestPath = $manifestPath;
    }

    public function find($alias)
    {
        $manifest = $this->getManifest();

        return isset($manifest[$alias]) ? $manifest[$alias] : null;
    }

    public function getManifest()
    {
        if (! is_null($this->manifest)) {
            return $this->manifest;
        }

        if (! file_exists($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = $this->files->getRequire($this->manifestPath);
    }

    public function build()
    {
        $this->manifest = $this->getClassNames()
            ->mapWithKeys(function ($class) {
                return [$class::getName() => $class];
            })->toArray();

        $this->write($this->manifest);

        return $this;
    }

    protected function write(array $manifest)
    {
        if (! is_writable(dirname($this->manifestPath))) {
            throw new Exception('The '.dirname($this->manifestPath).' directory must be present and writable.');
        }

        $this->files->put($this->manifestPath, '<?php return '.var_export($manifest, true).';', true);
    }

    public function getClassNames()
    {
        return collect($this->files->allFiles($this->path))
            ->map(function (SplFileInfo $file) {
                return app()->getNamespace().str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getPathname(), app_path().DIRECTORY_SEPARATOR)
                    // Str::after($file->getPathname(), app_path().'/')
                );

                return app()->getNamespace().
                    str($file->getPathname())
                        ->after(app_path().'/')
                        ->replace(['/', '.php'], ['\\', ''])->__toString();
            })
            ->filter(function ($class) {
                $class = cast_to_string($class);

                return is_subclass_of($class, Component::class) &&
                    ! (new ReflectionClass($class))->isAbstract();
            });
    }
}
