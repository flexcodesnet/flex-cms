<?php

namespace App\Http\Controllers\Panel;

use App\Models\Manufacturer;
use App\Models\Material;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class DashboardController extends PanelController
{
    public function index()
    {
        $this->data->title = sprintf(
            '%s%s %s',
            __('messages.fields.hello'),
            __('messages.fields.commas_symbol'),
            auth()->user()->name
        );
        $this->data->page_title = __('messages.fields.welcome');
        return view('panel.index', (array)$this->data);
    }
}
