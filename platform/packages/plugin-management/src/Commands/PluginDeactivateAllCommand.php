<?php

namespace FXC\PluginManagement\Commands;

use BaseHelper;
use FXC\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class PluginDeactivateAllCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:deactivate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate all plugins in /plugins directory';

    /**
     * @var PluginService
     */
    protected $pluginService;

    /**
     * PluginActivateCommand constructor.
     * @param PluginService $pluginService
     */
    public function __construct(PluginService $pluginService)
    {
        parent::__construct();

        $this->pluginService = $pluginService;
    }

    /**
     * @return int
     * @throws FileNotFoundException
     */
    public function handle()
    {
        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $this->pluginService->deactivate($plugin);
        }

        $this->info('Deactivated successfully!');

        return 0;
    }
}
