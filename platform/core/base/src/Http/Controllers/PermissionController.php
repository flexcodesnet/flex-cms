<?php


namespace FXC\Base\Http\Controllers;

use FXC\Base\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends PanelController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataFields(Permission::class);
    }

    public function data(Request $request)
    {
        $this->data->result = Permission::query()->parents()->get();
        return parent::data($request);
    }

    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function create(Request $request, $locale, $id = null): JsonResponse
    {
        if ($id != null) {
            Permission::query()->findOrFail($id);
        }

        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $this->data->model = new Permission;
        $this->data->model->title = $request->title;
        $this->data->model->parent_id = $request->id;
        $this->data->model->save();

        return parent::create($request, $locale);
    }

    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $locale, $id): JsonResponse
    {
//        Log::info('update');
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $this->data->model = Permission::query()->findOrFail($id);
        $this->data->model->title = $request->title;
        $this->data->model->save();
        $children = $this->data->model->children()->get();

        foreach ($children as $child) {
            $child->slug = $child->title;
            $child->save();
        }
        return parent::update($request, $locale, $id);
    }

    public function delete($locale, $id): JsonResponse
    {
        $this->data->model = Permission::query()->findOrFail($id);
        $this->data->model->roles()->detach();
        $children = $this->data->model->children()->get();
        foreach ($children as $child) {
            $child->roles()->detach();
            $child->delete();
        }
        return parent::delete($locale, $id);
    }
}
