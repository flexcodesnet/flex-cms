<?php

namespace App\Http\Controllers\Panel;

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
