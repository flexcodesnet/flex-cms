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
        $this->data->ths = ['panel.fields.title'];
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

    public function show($id)
    {
        $this->data->model = Role::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::show($id);
    }

    public function edit($id)
    {
        $this->data->model = Role::query()->findOrFail($id);
        /**
         * for treeview
         */
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');
        return parent::edit($id);
    }

    public function create(Request $request, $id = null)
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

        return parent::create($request);
    }

    public function update(Request $request, $id)
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

        return parent::update($request, $id);
    }

    public function delete($id)
    {
        Role::query()->findOrFail($id)->permissions()->detach();
        return parent::delete($id);
    }
}
