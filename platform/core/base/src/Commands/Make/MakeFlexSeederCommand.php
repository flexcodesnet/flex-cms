<?php

namespace FXC\Base\Console\Commands\Make;

use App\Console\Commands\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeFlexSeederCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:make:seeder {name}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate Seeder Extend from Base Seeder Command';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Seeder';

    /**
     * Get the stub file for the generator.
     * @return string
     */
    protected function getStub(): string
    {
        return $this->getStubTrait('seeder');
    }


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = class_basename($name);
        return $this->laravel->databasePath().'/seeders/'.$name.'.php';
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Seeder.'],
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
