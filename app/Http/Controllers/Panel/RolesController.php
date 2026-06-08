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

    public function data(Request $request)
    {
        $this->data->result = $this->accessibleRolesQuery();

        return parent::data($request);
    }

    public function show($id)
    {
        $this->data->model = $this->findAccessibleRole($id);
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');

        return parent::show($id);
    }

    public function edit($id)
    {
        $this->data->model = $this->findAccessibleRole($id);
        $this->data->values = $this->data->model->permissions()->pluck('permissions.id');

        return parent::edit($id);
    }

    public function create(Request $request, $id = null)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'permissions' => ['nullable', 'string'],
        ]);

        $this->data->model = new Role;
        $this->data->model->title = $request->title;
        $this->data->model->save();
        $this->syncPermissions($request);

        return parent::create($request);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'permissions' => ['nullable', 'string'],
        ]);

        $this->data->model = $this->findAccessibleRole($id);
        $this->data->model->title = $request->title;
        $this->data->model->save();
        $this->syncPermissions($request);

        return parent::update($request, $id);
    }

    public function delete($id)
    {
        $role = $this->findAccessibleRole($id);
        $role->permissions()->detach();
        $role->delete();

        $response = (object) [];
        $response->id = $id;
        $response->status = 'success';
        $response->message = __('panel.messages.delete.success');

        return response()->json((array) $response);
    }

    protected function accessibleRolesQuery()
    {
        return Role::query()->where('id', '>=', auth()->user()->role_id);
    }

    protected function findAccessibleRole($id)
    {
        return $this->accessibleRolesQuery()->findOrFail($id);
    }

    protected function syncPermissions(Request $request): void
    {
        if (blank($request->permissions)) {
            $this->data->model->permissions()->detach();

            return;
        }

        $permissionIds = array_values(array_filter(array_map('intval', explode(',', $request->permissions))));

        if (empty($permissionIds)) {
            $this->data->model->permissions()->detach();

            return;
        }

        $validIds = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('id')
            ->all();

        if (count($validIds) !== count($permissionIds)) {
            abort(422, 'Invalid permission selection.');
        }

        $this->data->model->permissions()->sync($validIds);
    }
}
