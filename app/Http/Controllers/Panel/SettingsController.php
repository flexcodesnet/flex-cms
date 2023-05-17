<?php

namespace App\Http\Controllers\Panel;

use App\Models\Setting;

class SettingsController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
        $this->data->slug = 'settings';
        $this->data->class = Setting::class;
        $this->data->base_title = 'messages.models.settings';
        $this->data->ths = ['messages.fields.title'];
        $this->data->fields = [
            [
                'slug' => 'title',
                'type' => 'text',
                'required' => true,
            ],
        ];
    }

    public function index($locale)
    {
        $this->data->model = Setting::query()->firstOrCreate([
            'id' => 1,
        ], []);

        if (request()->isMethod('PUT')) {
            $this->data->model->title = request()->title;
            $this->data->model->save();
            return parent::update(request(), $locale, $this->data->model->id);
        }

        $this->data->method = 'PUT';
        $this->data->action = route('panel.settings.index');
        $this->data->title = __($this->data->base_title . 'plural');
        $this->data->submit_button = 'edit';
        return parent::index($locale);
    }
}
