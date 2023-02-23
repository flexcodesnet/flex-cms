<?php

namespace FXC\Base\Http\Controllers;

use App\Table\SeoMetaField;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class PanelController extends BaseController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->data->menus = json_decode(json_encode(include(config_path('/menu.php'))));
    }

    /**
     * @param  Request  $request
     * @return void
     * @throws \Exception
     */
    public function data(Request $request)
    {
        if (!isset($this->data->result)) {
            $this->data->result = call_user_func(sprintf('%s::query', $this->data->class))->latest()->get();
        }
        return $this->datatable($this->data->result);
    }

    /**
     * @param $result
     * @return never
     * @throws \Exception
     */
    public function datatable($result)
    {
        try {
            $response = datatables()->of($result)
                ->addColumn('id', function ($row) {
                    return $this->label($row);
                });
            if (request()->ajax()) {
                $response = $response->addColumn('action', function ($row) {
                    return $this->action($row);
                });
            }
            if ($this->data->availableFields) {
                foreach ($this->data->availableFields as $field) {
                    $response = $response->addColumn($field->slug, function ($row) use ($field) {
                        return $this->dataTableField($field, $row);
                    });
                }
            }
            if (isset($this->data->need_to_check_locale) && $this->data->need_to_check_locale) {
                foreach (get_locales() as $local) {
                    $response->addColumn($local, function ($row) use ($local) {
                        return $row->wasTranslated($local) ?
                            $this->label($row, '✓', 'success') :
                            $this->label($row, 'X', 'danger');
                    });
                }
            }

            return $response->make(true);
        } catch (Exception $e) {
            return abort(500, $e->getMessage());
        }
    }

    /**
     * @param $row
     * @param $title
     * @param $bg
     * @return Factory|View|Application
     */
    private function label($row, $title = null, $bg = 'primary')
    {
        $this->data->row = $row;
        $this->data->title = $title;
        $this->data->bg = $bg;
        return view('panel.include.datatable.label', (array) $this->data);
    }

    /**
     * @param $row
     * @return Factory|View|Application
     */
    private function action($row)
    {
        $this->data->row = $row;
        return view('panel.include.datatable.action', (array) $this->data);
    }

    /**
     * @param $field
     * @param $row
     * @return Factory|View|Application
     */
    private function dataTableField($field, $row)
    {
        $this->data->row = $row;
        $this->data->field = $field;
        return view('panel.include.datatable.field', (array) $this->data);
    }

    /**
     * @param $locale
     * @return Factory|View|Application
     */
    public function index($locale)
    {
        $this->data->title = sprintf('messages.models.plural.%s', $this->data->moduleName);
        if (isset($this->data->need_to_check_locale) && $this->data->need_to_check_locale) {
            foreach (get_locales() as $locale) {
                $this->data->ths[] = __('messages.languages.'.$locale.'.title');
            }
        }

        return $this->getBaseView('index');
    }

    /**
     * @param  string  $viewName
     * @return Application|Factory|View
     */
    private function getBaseView(string $viewName)
    {
        $view = sprintf('panel.%s.%s', $this->data->moduleName, $viewName);
        if (!view()->exists($view)) {
            $view = sprintf('panel.%s.%s', 'base', $viewName);
        }
        return view($view, (array) $this->data);
    }

    /**
     * @param $locale
     * @param $id
     * @return Factory|View|Application
     */
    public function add($locale, $id = null)
    {
        $this->data->method = 'POST';
        $this->data->action = route(!isset($id) ? sprintf('panel.%s.create', $this->data->moduleName) : sprintf('panel.%s.model.create', $this->data->moduleName),
            isset($id) ? [app()->getLocale(), $id] : app()->getLocale());
        $this->data->title = sprintf('%s %s', __('messages.buttons.add'), __(sprintf('messages.models.single.%s', $this->data->moduleName)));
        $this->data->submit_button = 'save';
        $this->setDataModelAttribute($id);

        return $this->getBaseView('model');
    }

    /**
     * @param $locale
     * @param $id
     * @return Factory|View|Application
     */
    public function show($locale, $id)
    {
        $this->setDataModelAttribute($id);

        $this->data->method = 'GET';
        $this->data->action = '';
        $this->data->title = sprintf('%s %s %s', __('messages.buttons.show'), __(sprintf('messages.models.single.%s', $this->data->moduleName)), $id);

        return $this->getBaseView('model');
    }

    /**
     * @param $locale
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($locale, $id)
    {
        $this->setDataModelAttribute($id);

        $this->data->method = 'PUT';
        $this->data->action = route(sprintf('panel.%s.update', $this->data->moduleName), [app()->getLocale(), $id]);
        $this->data->title = sprintf('%s %s %s', __('messages.buttons.edit'), __(sprintf('messages.models.single.%s', $this->data->moduleName)), $id);
        $this->data->submit_button = 'update';
        return $this->getBaseView('model');
    }


    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function create(Request $request, $locale, $id = null)
    {
        $this->createNewRecord($request);

        $response = (object) [];
        $response->status = 'success';
        $response->message = __('messages.messages.success.create');
        $response->model = $this->data->model;
        if (route_is_defined(sprintf('panel.%s.index', $this->data->moduleName))) {
            $response->redirect = route(sprintf('panel.%s.index', $this->data->moduleName), [app()->getLocale()]);
        }
        return response()->json((array) $response);
    }

    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $locale, $id)
    {
        $this->updateExistsRecord($request, $id);
        $response = (object) [];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('messages.messages.success.edit');
        $response->model = $this->data->model;
        if (route_is_defined(sprintf('panel.%s.index', $this->data->moduleName))) {
            $response->redirect = route(sprintf('panel.%s.index', $this->data->moduleName), [app()->getLocale()]);
        }
        return response()->json((array) $response);
    }

    /**
     * @param $locale
     * @param $id
     * @return Factory|View|Application
     */
    public function seo($locale, $id)
    {
        $this->setDataModelAttribute($id);
        $this->data->seoMetaTags = optional($this->data->model->meta_tags)->groupBy('locale');

        $this->data->method = 'POST';
        $this->data->action = route(sprintf('panel.%s.seo.update', $this->data->moduleName), [app()->getLocale(), $id]);
        $this->data->title = sprintf('%s %s %s', __('messages.buttons.update_seo'), __(sprintf('messages.models.single.%s', $this->data->moduleName)), $id);
        $this->data->submit_button = 'update_seo';
        $this->data->fields = (new SeoMetaField())->getFields();

        return $this->getBaseView('seo');
    }

    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function seoUpdate(Request $request, $locale, $id)
    {
        $this->setDataModelAttribute($id);

        $this->updateSeoMetaRecord($request, $id);

        $response = (object) [];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('messages.messages.success.seo_update');
        $response->model = $this->data->model;
        if (route_is_defined(sprintf('panel.%s.index', $this->data->moduleName))) {
            $response->redirect = route(sprintf('panel.%s.index', $this->data->moduleName), [app()->getLocale()]);
        }
        return response()->json((array) $response);
    }

    /**
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function delete($locale, $id)
    {
        call_user_func(sprintf('%s::query', $this->data->class))
            ->findOrFail($id)->delete(); // (As of PHP 5.2.3)
        $response = (object) [];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('messages.messages.success.delete');
        return response()->json((array) $response);
    }

}
