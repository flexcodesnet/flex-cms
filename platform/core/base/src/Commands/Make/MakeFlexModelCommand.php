<?php

namespace FXC\Base\Console\Commands\Make;

use App\Console\Commands\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFlexModelCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:make:model 
                        {name : model class name}
                        {--a|--all : Generate a migration, seeder, factory, and resource controller for the model. }
                        {--c|--controller : Create a new controller for the model. }
                        {--f|--factory : Create a new factory for the model. }
                        {--force : Create the class even if the model already exists. }
                        {--m|--migration : Create a new migration file for the model. }
                        {--fl|--field : Create a new field class for the model. }
                        {--e|--event : Create a new events for the model. }
                        {--l|--listener : Create a new listeners for the model. }
                        {--nt|--notification : Create a new notifications for the model. }
                        {--s|--seed : Create a new seeder file for the model. }
                        {--p|--pivot : Indicates if the generated model should be a custom intermediate table model. }
                        {--r|--resource : Indicates if the generated controller should be a resource controller. }
                        {--ig|--ignore: exist}
                        {--api : Indicates if the generated controller should be an API controller. }';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Generate a new Eloquent Model Extend from flex cms package model class';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Model';

    /**
     * @return false
     * @throws FileNotFoundException
     */
    public function handle(): bool
    {
            if (parent::handle() === false && !$this->option('force')) {
                return false;
            }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('resource', true);
            $this->input->setOption('field', true);
            $this->input->setOption('event', true);
            $this->input->setOption('listener', true);
            $this->input->setOption('notification', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->createController();
        }

        if ($this->option('field')) {
            $this->createField();
        }

        if ($this->option('event')) {
            $this->createEvents();
        }

        if ($this->option('listener')) {
            $this->createListeners();
        }

        if ($this->option('notification')) {
            $this->createNotifications();
        }

        return true;
    }

    /**
     * Create a model factory for the model.
     * @return void
     */
    protected function createFactory()
    {
        $name = $this->getModelNameTrait();

        $this->call('make:factory', [
            'name'    => "{$name}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Create a migration file for the model.
     * @return void
     */
    protected function createMigration()
    {
        $table = $this->getTableNameTrait();

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        try {
            $this->call('make:migration', [
                'name'     => "create_{$table}_table",
                '--create' => $table,
            ]);
        } catch (\Exception $exp) {
            $this->error("Migration already exists!");
        }
    }

    /**
     * Create a seeder file for the model.
     * @return void
     */
    protected function createSeeder()
    {
        $name = $this->getModelNameTrait();

        $this->call('flex:make:seeder', [
            'name' => "{$name}Seeder",
        ]);
    }

    /**
     * Create a controller for the model.
     * @return void
     */
    protected function createController()
    {
        $name = $this->getModelNameTrait();

        if (!class_exists("App\\Http\\Controllers\\{$name}Controller")) {
            $this->call('flex:make:controller', array_filter([
                'name' => "{$name}Controller",
            ]));
        }
    }

    /**
     * @return void
     */
    protected function createField()
    {
        $name = $this->getModelNameTrait();

        if (!class_exists("App\\Table\\{$name}Field")) {
            $this->call('flex:make:field', ['name' => "{$name}Field"]);
        }
    }

    /**
     * @return void
     */
    private function createEvents()
    {
        $name = $this->getModelNameTrait();
        if (!File::exists(app_path("events\\{$name}"))) {
            File::makeDirectory(app_path("events\\{$name}"));
        }
        $types = ["Created", "Updated"];
        foreach ($types as $type) {
            $event_name = "{$name}\\{$name}{$type}Event"; // Post\\PostCreatedEvent

            // App\Event\Post\PostCreatedEvent
            if (!class_exists("App\\Events\\{$event_name}")) {

                $this->call('flex:make:event', [
                    'name'   => $event_name,
                    '--type' => $type,
                ]);
            }
        }
    }

    /**
     * @return void
     */
    private function createListeners()
    {
        $name = $this->getModelNameTrait();
        if (!File::exists(app_path("listeners\\{$name}"))) {
            File::makeDirectory(app_path("listeners\\{$name}"));
        }
        $types = ["Created", "Updated"];

        foreach ($types as $type) {
            $listener_name = "{$name}\\{$name}{$type}Listener"; // Post\\PostCreatedListener
            // App\Listeners\Post\PostCreatedListener
            if (!class_exists("App\\Listeners\\{$listener_name}")) {
                $this->call('flex:make:listener', [
                    'name'   => $listener_name,
                    '--type' => $type,
                ]);
            }
        }
    }

    /**
     * @return void
     */
    private function createNotifications()
    {
        $name = $this->getModelNameTrait();
        if (!File::isDirectory(app_path("notifications\\{$name}"))) {
            File::makeDirectory(app_path("notifications\\{$name}"));
        }
        $types = ["Create", "Update"];
        foreach ($types as $type) {
            $notification_name = "{$name}\\{$type}{$name}Notification"; // Contact\\CreateContactNotification
            // App\Notification\Contact\CreateContact
            if (!class_exists("App\\Notifications\\{$notification_name}")) {
                $this->call('flex:make:notification', [
                    'name'   => $notification_name,
                    '--type' => $type,
                ]);
            }
        }
    }


    /**
     * Get the stub file for the generator.
     * @return string
     */
    protected function getStub(): string
    {
        return $this->getStubTrait('model');
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
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
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
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['field', 'cl', InputOption::VALUE_NONE, 'Create a new field class for the model'],
            ['event', 'e', InputOption::VALUE_NONE, 'Create a new events for the model'],
            ['listener', 'l', InputOption::VALUE_NONE, 'Create a new listeners for the model'],
            ['notification', 'nt', InputOption::VALUE_NONE, 'Create a new notifications for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder file for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['ignore', 'ig', InputOption::VALUE_NONE, 'Indicates if the generated model exists generate all without model.'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
        ];
    }


}
