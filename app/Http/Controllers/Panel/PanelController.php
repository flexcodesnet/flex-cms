<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class  PanelController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->data->menus = [
            [
                'title' => 'panel.fields.manage_settings',
                'hrefs' => [
                    'panel.settings.index',
                ],
                'children' => [
                    [
                        'title' => 'panel.models.settings.plural',
                        'icon' => 'fa-cogs',
                        'href' => 'panel.settings.index',
                        'active' => 'panel.settings.index',
                    ],
                ],
            ],
            [
                'title' => 'panel.fields.manage_users',
                'hrefs' => [
                    'panel.users.index',
                    'panel.roles.index',
                    'panel.permissions.index',
                ],
                'children' => [
                    [
                        'title' => 'panel.models.users.plural',
                        'icon' => 'fa-user',
                        'href' => 'panel.users.index',
                        'active' => 'panel.users.*',
                    ],
                    [
                        'title' => 'panel.models.roles.plural',
                        'icon' => 'fa-user-tag',
                        'href' => 'panel.roles.index',
                        'active' => 'panel.roles.*',
                    ],
                    [
                        'title' => 'panel.models.permissions.plural',
                        'icon' => 'fa-user-lock',
                        'href' => 'panel.permissions.index',
                        'active' => 'panel.permissions.*',
                    ],
                ],
            ],
        ];
    }

    private function label($row, $title = null, $bg = 'primary')
    {
        $this->data->row = $row;
        $this->data->title = $title;
        $this->data->bg = $bg;
        return view('panel.include.datatable.label', (array)$this->data);
    }

    private function action($row)
    {
        $this->data->row = $row;
        return view('panel.include.datatable.action', (array)$this->data);
    }

    public function data(Request $request)
    {
        if (!isset($this->data->result))
            $this->data->result = call_user_func(sprintf('%s::query', $this->data->class))->latest();
        return $this->datatable($this->data->result);
    }

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
            if (isset($this->data->need_to_check_locale) && $this->data->need_to_check_locale) {
                foreach (config('app.locales') as $local) {
                    $response->addColumn($local, function ($row) use ($local) {
                        return $row->wasTranslated($local) ?
                            $this->label($row, '✓', 'success') :
                            $this->label($row, 'X', 'danger');
                    });
                }
            }

            return $response->make(true);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function index()
    {
        $this->data->title = sprintf('panel.models.%s.plural', $this->data->slug);
        if (isset($this->data->need_to_check_locale) && $this->data->need_to_check_locale) {
            foreach (config('app.locales') as $locale) {
                $this->data->ths[] = __('panel.languages.' . $locale . '.title');
            }
        }
        return view(sprintf('panel.%s.index', $this->data->slug), (array)$this->data);
    }

    public function add($id = null)
    {
        $this->data->method = 'POST';
        $this->data->action = route(!isset($id) ? sprintf('panel.%s.create', $this->data->slug) : sprintf('panel.%s.children.model.create', $this->data->slug), isset($id) ? [$id] : []);
        $this->data->title = sprintf('%s %s', __('panel.buttons.add'), __(sprintf('panel.models.%s.single', $this->data->slug)));
        $this->data->submit_button = 'add';
        if (!is_null(request()->query('id')))
            $this->data->model = call_user_func(sprintf('%s::query', $this->data->class))->findOrFail(request()->query('id'));
        return view(sprintf('panel.%s.model', $this->data->slug), (array)$this->data);
    }

    public function show($id)
    {
        if (!isset($this->data->model))
            $this->data->model = call_user_func(sprintf('%s::query', $this->data->class))
                ->findOrFail($id);
        $this->data->method = 'GET';
        $this->data->action = '';
        $this->data->title = sprintf('%s %s %s', __('panel.buttons.show'), __(sprintf('panel.models.%s.single', $this->data->slug)), $id);
        return view(sprintf('panel.%s.model', $this->data->slug), (array)$this->data);
    }

    public function edit($id)
    {
        if (!isset($this->data->model))
            $this->data->model = call_user_func(sprintf('%s::query', $this->data->class))
                ->findOrFail($id);
        $this->data->method = 'PUT';
        $this->data->action = route(sprintf('panel.%s.update', $this->data->slug), [$id]);
        $this->data->title = sprintf('%s %s %s', __('panel.buttons.edit'), __(sprintf('panel.models.%s.single', $this->data->slug)), $id);
        $this->data->submit_button = 'edit';
        return view(sprintf('panel.%s.model', $this->data->slug), (array)$this->data);
    }

    public function create(Request $request, $id = null)
    {
        $response = (object)[];
        $response->status = 'success';
        $response->message = __('panel.messages.create.success');
        $response->model = $this->data->model;
        if (route_is_defined(sprintf('panel.%s.index', $this->data->slug)))
            $response->redirect = route(sprintf('panel.%s.index', $this->data->slug), []);
        return response()->json((array)$response);
    }

    public function update(Request $request, $id)
    {
        $response = (object)[];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('panel.messages.edit.success');
        $response->model = $this->data->model;
        if (route_is_defined(sprintf('panel.%s.index', $this->data->slug)))
            $response->redirect = route(sprintf('panel.%s.index', $this->data->slug), []);
        return response()->json((array)$response);
    }

    public function delete($id)
    {
        call_user_func(sprintf('%s::query', $this->data->class))
            ->findOrFail($id)->delete(); // (As of PHP 5.2.3)
        $response = (object)[];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('panel.messages.delete.success');
        return response()->json((array)$response);
    }
}
