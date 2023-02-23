<?php

namespace App\Http\Controllers\Panel;

use App\Models\Setting;
use FXC\Base\Http\Controllers\PanelController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class SettingController extends PanelController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataFields(Setting::class);
    }

    /**
     * @param $locale
     * @return Factory|View|Application
     */
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
        $this->data->action = route('panel.settings.index', app()->getLocale());
        $this->data->title = sprintf('messages.models.plural.%s', $this->data->moduleName);
        $this->data->submit_button = 'edit';
        return parent::index($locale);
    }
}
