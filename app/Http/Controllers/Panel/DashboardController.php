<?php

namespace App\Http\Controllers\Panel;

class DashboardController extends PanelController
{
    public function index()
    {
        $this->data->title = sprintf(
            '%s%s %s',
            __('panel.fields.hello'),
            __('panel.fields.commas_symbol'),
            auth()->user()->name
        );
        $this->data->page_title = __('panel.fields.welcome');
        $this->data->menu_cards = $this->getAllowedMenuCards();

        return view('panel.index', (array)$this->data);
    }
}
