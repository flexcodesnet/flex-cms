<?php

namespace FXC\Base\Console\Commands\Make;

use App\Console\Commands\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFlexListenerCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:make:listener 
                            {name : listener class name}
                            {--t|--type=Create : Type of listener Created or Updated}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate Model Listeners Extend from flex cms package listeners';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = '{name} Listener';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->getStubTrait('listener');
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
        return $rootNamespace.'\Listeners';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model.'],
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
        $moduleName = $this->getModelNameTrait();
        $typeOption = $this->option('type');

        $listenerName = "\\App\Listeners\\{$moduleName}\\{$moduleName}{$typeOption}Listener";
        $eventName = "\\App\Events\\{$moduleName}\\{$moduleName}{$typeOption}Event";
        $stub = str_replace('DummyEventName', $eventName, $stub);
        $stub = str_replace('DummyListenerName', $listenerName, $stub);
        return $this->replaceClassTrait($stub);
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['type', 't', InputOption::VALUE_NONE, 'Create a new notification with type create or update'],
        ];
    }
}
