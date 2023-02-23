<?php

namespace FXC\Base\Console\Commands\Make;

use App\Console\Commands\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeFlexFieldCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:make:field {name}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate Model Extend from flex cms package';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Field';

    /**
     * Get the stub file for the generator.
     * @return string
     */
    protected function getStub(): string
    {
        return $this->getStubTrait('field');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Table';
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the column.'],
        ];
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $stub = parent::replaceClass($stub, $name);
        return $this->replaceClassTrait($stub);
    }

}
