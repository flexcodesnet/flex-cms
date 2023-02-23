<?php

namespace FXC\Base\Console\Commands\Make;

use App\Console\Commands\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFlexNotificationCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:make:notification
                            {name : notification class name}
                            {--t|--type=Create : Type of notification Create or Update}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate Model notifications Extend from flex cms package notifications';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Notification';

    /**
     * Get the stub file for the generator.
     * @return string
     */
    protected function getStub(): string
    {
        // notification.create.stub , notification.update.stub
        $this->type = Str::lower($this->option('type') ?? 'create');
        return $this->getStubTrait("notification.{$this->type}");
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
        return $rootNamespace.'\Notifications';
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
        $stub = str_replace('DummyTitle', $moduleName, $stub);
        return $this->replaceClassTrait($stub);
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model.'],
        ];
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
