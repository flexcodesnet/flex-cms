<?php

namespace App\Http\Controllers\Panel;

use App\Models\Manufacturer;
use App\Models\Material;
use App\Models\Order;
use App\Models\Supplier;
use FXC\Base\Http\Controllers\PanelController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DashboardController extends PanelController
{
    /**
     * @param  string|null  $locale
     * @return Factory|View|Application
     */
    public function index($locale)
    {
        $this->data->title = sprintf(
            '%s%s %s',
            __('messages.fields.hello'),
            __('messages.fields.commas_symbol'),
            auth()->user()->name
        );
        $this->data->page_title = __('messages.fields.welcome');
        return view('panel.index', (array) $this->data);
    }
}
