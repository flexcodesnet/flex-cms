<?php

namespace FXC\PluginManagement\Commands;

use FXC\Base\Helpers\BaseHelper;
use FXC\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;

class PluginActivateAllCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:activate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate all plugins in /plugins directory';

    /**
     * @var PluginService
     */
    protected $pluginService;

    /**
     * PluginActivateCommand constructor.
     * @param  PluginService  $pluginService
     */
    public function __construct(PluginService $pluginService)
    {
        parent::__construct();

        $this->pluginService = $pluginService;
    }

    public function handle(): int
    {
        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $this->pluginService->activate($plugin);
        }

        $this->info('Activated successfully!');

        return self::SUCCESS;
    }
}
