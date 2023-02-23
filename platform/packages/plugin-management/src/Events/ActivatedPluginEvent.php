<?php

namespace FXC\PluginManagement\Events;

use FXC\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class ActivatedPluginEvent extends Event
{
    use SerializesModels;

    /**
     * @var string
     */
    public string $plugin;

    /**
     * ActivatedPluginEvent constructor.
     * @param  string  $plugin
     */
    public function __construct(string $plugin)
    {
        $this->plugin = $plugin;
    }
}
