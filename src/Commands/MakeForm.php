<?php

namespace Kompo\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeForm extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kompo:form {name} {--demo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new kompo Form class  ⚡';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyClass', $this->argument('name'), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__.'/stubs/kompo-'.($this->option('demo') ? 'demo-' : '').'form.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The class name of the form.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['demo', InputOption::VALUE_NONE, 'Copy the Kompo Demo Form for first render in quick installation'],
        ];
    }
}
