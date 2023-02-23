<?php

namespace FXC\Base\Console\Commands\Remove;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use function class_basename;

class RemoveFlexModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'flex:remove:model 
                        {name : model class name}
                        {--a|--all : Generate a migration, seeder, factory, and resource controller for the model. }
                        {--c|--controller : Create a new controller for the model. }
                        {--f|--factory : Create a new factory for the model. }
                        {--force : Create the class even if the model already exists. }
                        {--m|--migration : Create a new migration file for the model. }
                        {--cl|--column : Create a new Column class for the model. }
                        {--e|--event : Create a new events for the model. }
                        {--l|--listener : Create a new listeners for the model. }
                        {--nt|--notification : Create a new notifications for the model. }
                        {--s|--seed : Create a new seeder file for the model. }
                        {--b|--base : Indicates if the generated model should be a custom intermediate table model. }
                        {--p|--pivot : Indicates if the generated model should be a custom intermediate table model. }
                        {--r|--resource : Indicates if the generated controller should be a resource controller. }
                        {--api : Indicates if the generated controller should be an API controller. }
                        {--model}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Remove Created model flex make command.';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'Model';

    /**
     * @return void
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('resource', true);
            $this->input->setOption('base', true);
            $this->input->setOption('column', true);
            $this->input->setOption('event', true);
            $this->input->setOption('listener', true);
            $this->input->setOption('notification', true);
        }

        $this->removeModel();

        if ($this->option('factory')) {
            $this->removeFactory();
        }

        if ($this->option('migration')) {
            $this->removeMigration();
        }

        if ($this->option('seed')) {
            $this->removeSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->removeController();
        }

        if ($this->option('column')) {
            $this->removeColumn();
        }

        if ($this->option('event')) {
            $this->removeEvents();
        }

        if ($this->option('listener')) {
            $this->removeListeners();
        }

        if ($this->option('notification')) {
            $this->removeNotifications();
        }
    }

    /**
     * Create a model factory for the model.
     * @return void
     */
    protected function removeModel()
    {
        $name = Str::studly($this->argument('name'));
        $folder_name = "app/Models";
        $file_name = "{$name}.php";

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Model Removed Successfully");
    }

    /**
     * Create a model factory for the model.
     * @return void
     */
    protected function removeFactory()
    {
        $factory = Str::studly($this->argument('name'));
        $folder_name = "database/factories";
        $file_name = "{$factory}Factory.php";

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Factories Removed Successfully");
    }

    /**
     * Create a migration file for the model.
     * @return void
     */
    protected function removeMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $folder_name = "database/migrations";

        $migration_name = "create_{$table}_table";

        $file_name = $this->findFileNameBySubName($folder_name, $migration_name);

        if (!$file_name) {
            return;
        }

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Migrations Removed Successfully");
    }

    /**
     * Create a seeder file for the model.
     * @return void
     */
    protected function removeSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $folder_name = "database/seeders";
        $file_name = "{$seeder}Seeder.php";

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Seeders Removed Successfully");
    }

    /**
     * Create a controller for the model.
     * @return void
     */
    protected function removeController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $folder_name = "app/Http/controllers";
        $file_name = "{$controller}Controller.php";

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Controllers Removed Successfully");
    }

    /**
     *
     */
    protected function removeColumn()
    {
        $column = Str::studly(class_basename($this->argument('name')));

        $folder_name = "app/Support";
        $file_name = "{$column}Cols.php";

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Columns Removed Successfully");
    }

    /**
     *
     */
    private function removeEvents()
    {
        $name = Str::studly(class_basename($this->argument('name')));

        $folder_name = "app/Events";
        $file_name = $name;

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Events Removed Successfully");
    }

    /**
     *
     */
    private function removeNotifications()
    {
        $name = Str::studly(class_basename($this->argument('name')));

        $folder_name = "app/Notifications";
        $file_name = "{$name}"; // Event/Contact

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Notifications Removed Successfully");
    }


    /**
     *
     */
    private function removeListeners()
    {
        $name = Str::studly(class_basename($this->argument('name')));

        $folder_name = "app/Listeners";
        $file_name = $name;

        $file_path = base_path("{$folder_name}/{$file_name}");

        $this->removeFileOrFolder($file_path);

        $this->infoMessage("Listeners Removed Successfully");
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model.'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['column', 'cl', InputOption::VALUE_NONE, 'Create a new Column class for the model'],
            ['event', 'e', InputOption::VALUE_NONE, 'Create a new events for the model'],
            ['listener', 'l', InputOption::VALUE_NONE, 'Create a new listeners for the model'],
            ['notification', 'nt', InputOption::VALUE_NONE, 'Create a new notifications for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder file for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['base', 'b', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
        ];
    }

    /**
     * @param  string  $file_path
     */
    private function removeFileOrFolder(string $file_path)
    {
        if (is_dir($file_path)) {
            if (File::deleteDirectory($file_path)) {
                $this->info("Folder Removed => {$file_path}.");
            } else {
                $this->warn("Folder does not exists => {$file_path}.");
            }
        } else {
            if (File::exists($file_path)) {
                File::delete($file_path);
                $this->info("File Removed => {$file_path} .");
            } else {
                $this->warn("File does not exists => {$file_path}.");
            }
        }
    }

    /**
     * @param  string  $message
     */
    private function infoMessage(string $message)
    {
        $this->info("{$message} \n-----------------------------------------------------------------\n");
    }

    /**
     * @param  string  $folder_name
     * @param  string  $migration_name
     * @return string|null
     */
    private function findFileNameBySubName(string $folder_name, string $migration_name): ?string
    {
        $file_name = null;
        $migration_files = File::allFiles($folder_name);
        foreach ($migration_files as $migration_file) {
            if (Str::contains($migration_file->getFileName(), $migration_name)) {
                $file_name = $migration_file->getFileName();
                break;
            }
        }
        if (!$file_name) {
            $this->warn("File does not exists=> {$file_name}");
        }

        return $file_name;
    }

}