<?php

namespace FXC\Base\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait CommandTrait
{
    /**
     * @var string
     */
    private string $command_type;

    /**
     * @return string
     */
    public function getStubPath(): string
    {
        return base_path('platform/cms/core/stubs');
    }

    /**
     * @return string
     */
    public function getStubName(): string
    {
        $stubName = $this->argument('name');
        return Str::snake($stubName);
    }

    /**
     * @param  string|null  $name
     * @return string
     */
    protected function getModelNameTrait(string $name = null): string
    {
        if (!$name) {
            $name = $this->argument('name');
        }

        $name = Str::studly($name);

        $moduleName = class_basename($name);

        $moduleName = str_replace(
            [$this->getCommandType(), "created", "create", "updated", "update", "notification", "listener", "event", "field", 'controller'],
            ['', '', '', '', '', '', '', '', '', ''],
            Str::snake($moduleName)
        );

        return Str::studly($moduleName);
    }


    /**
     * @return void
     */
    protected function getTableNameTrait(): string
    {
        $modelClass = $this->getModelClass();
        if (class_exists($modelClass)) {
            return app($modelClass)->getTable();

        }
        $model = class_basename($modelClass);
        return Str::plural(Str::lower(Str::snake($model)));
    }


    /**
     * @param $folder
     * @param  null  $fileName
     * @return string
     */
    public function getStubTrait($folder, $fileName = null): string
    {
        $path = $this->getStubPath();
        if (!$fileName) {
            $stubName = $this->getStubName();
            $fileName = "{$stubName}";
        }
        $filePath = "{$path}/$folder/{$fileName}.stub";

        if (File::exists($filePath)) {
            return $filePath;
        }

        return "$path/{$folder}.stub";
    }

    /**
     * @param  string  $stub
     * @return array|string|string[]
     */
    public function replaceClassTrait(string $stub)
    {
        $modelClass = $this->getModelClass();
        $fieldClass = $this->getFieldClass();
        $table = $this->getTableNameTrait();
        $url = str_replace('_', '-', $table);
        $permissionName = $this->getModelNameTrait();

        $stub = str_replace('DummyFieldName', $fieldClass, $stub);
        $stub = str_replace('DummyTableName', $table, $stub);
        $stub = str_replace('DummyModelName', $modelClass, $stub);
        $stub = str_replace('DummyPermissionName', $permissionName, $stub);
        $stub = str_replace('DummyURL', $url, $stub);

        return $stub;
    }


    /**
     * @return string
     */
    public function getCommandType(): string
    {
        $commandType = $this->type ?? $this->command_type;
        if ($commandType) {
            $commandType = Str::lower($commandType);
        }
        return $commandType;
    }

    /**
     * @param  mixed  $command_type
     */
    public function setCommandType($command_type): void
    {
        $this->command_type = $command_type;
    }

    /**
     * @param  string|null  $name
     * @return string
     */
    public function getModelClass(string $name = null): string
    {
        if (!$name) {
            $name = $this->getModelNameTrait($name);
        }

        return "\\App\\Models\\{$name}";
    }

    /**
     * @param  string|null  $name
     * @return string
     */
    public function getFieldClass(string $name = null): string
    {
        if (!$name) {
            $name = $this->getModelNameTrait($name);
        }

        return "\\App\\Table\\{$name}Field";
    }
}