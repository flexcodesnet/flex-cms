<?php

namespace App\Http\Controllers\Panel;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
        $this->data->slug = 'roles';
        $this->data->class = Role::class;
        $this->data->ths = ['messages.fields.title'];
        $this->data->fields = [
            [
                'slug' => 'title',
                'type' => 'text',
                'required' => true,
            ],
            [
                'slug' => 'permissions',
                'type' => 'treeview',
                'model' => Permission::query()->first(),
            ],
        ];
    }

    public function show($locale, $id)
    {
        $this->data->model = Role::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::show($locale, $id);
    }

    public function edit($locale, $id)
    {
        $this->data->model = Role::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::edit($locale, $id);
    }

    public function create(Request $request, $locale, $id = null)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $this->data->model = new Role;
        $this->data->model->title = $request->title;
        $this->data->model->save();
        $this->data->model->permissions()->detach();
        if (!$request->permissions == '') {
            $request->permissions = explode(",", $request->permissions);
            $this->data->model->permissions()->attach($request->permissions);
        }

        return parent::create($request, $locale);
    }

    public function update(Request $request, $locale, $id)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $this->data->model = Role::query()->findOrFail($id);
        $this->data->model->title = $request->title;
        $this->data->model->save();
        $this->data->model->permissions()->detach();
        if (!$request->permissions == '') {
            $request->permissions = explode(",", $request->permissions);
            $this->data->model->permissions()->attach($request->permissions);
        }

        return parent::update($request, $locale, $id);
    }

    public function delete($locale, $id)
    {
        Role::query()->findOrFail($id)->permissions()->detach();
        return parent::delete($locale, $id);
    }
}
