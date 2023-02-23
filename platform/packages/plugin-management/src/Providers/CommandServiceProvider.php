<?php

namespace FXC\PluginManagement\Providers;

use FXC\PluginManagement\Commands\PluginActivateAllCommand;
use FXC\PluginManagement\Commands\PluginActivateCommand;
use FXC\PluginManagement\Commands\PluginAssetsPublishCommand;
use FXC\PluginManagement\Commands\PluginDeactivateAllCommand;
use FXC\PluginManagement\Commands\PluginDeactivateCommand;
use FXC\PluginManagement\Commands\PluginRemoveAllCommand;
use FXC\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateCommand::class,
            PluginDeactivateAllCommand::class,
            PluginRemoveCommand::class,
            PluginRemoveAllCommand::class,
        ]);
    }
}
