<?php

namespace FXC\PluginManagement\Commands;

use BaseHelper;
use FXC\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class PluginRemoveAllCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:remove:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all plugins in /plugins directory';

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
     */
    public function handle()
    {
        if (!$this->confirmToProceed('Are you sure you want to remove ALL plugins?', true)) {
            return 1;
        }

        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $this->pluginService->remove($plugin);
        }

        $this->info('Removed successfully!');

        return 0;
    }
}
