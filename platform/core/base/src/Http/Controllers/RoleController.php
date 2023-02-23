<?php


namespace FXC\Base\Http\Controllers;

use FXC\Base\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends PanelController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->className = Role::class;
        $this->setDataFields($this->className);
    }

    /**
     * @param $locale
     * @param $id
     * @return Application|Factory|View
     */
    public function show($locale, $id)
    {
        $this->data->model = $this->className::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::show($locale, $id);
    }

    /**
     * @param $locale
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($locale, $id)
    {
        $this->data->model = $this->className::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::edit($locale, $id);
    }

    /**
     * @param  Request  $request
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function create(Request $request, $locale, $id = null): JsonResponse
    {
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
        return parent::update($request, $locale, $id);
    }

    /**
     * @param $locale
     * @param $id
     * @return JsonResponse
     */
    public function delete($locale, $id): JsonResponse
    {
        $this->className::query()->findOrFail($id)->permissions()->detach();
        return parent::delete($locale, $id);
    }
}
