<?php

namespace Kompo\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeMenu extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kompo:menu {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new kompo Menu class  ⚡';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Menu';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        return str_replace('{name}', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/stubs/kompo-menu.stub';
    }
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Menus';
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The class name of the Menu.']
        ];
    }

}
