<?php

namespace App\Http\Controllers\Panel;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionsController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
        $this->data->slug = 'permissions';
        $this->data->class = Permission::class;
        $this->data->ths = ['messages.fields.title'];
        $this->data->fields = [
            [
                'slug' => 'title',
                'type' => 'text',
                'required' => true,
            ],
            [
                'slug' => 'slug',
                'type' => 'text',
                'required' => false,
                'disabled' => true,
            ],
            [
                'slug' => 'children',
                'type' => 'children',
                'ths' => ['title'],
            ],
        ];
    }

    public function data(Request $request)
    {
        $this->data->result = Permission::query()->parents()->get();
        return parent::data($request);
    }

    public function create(Request $request, $locale, $id = null)
    {
        if ($id != null)
            Permission::query()->findOrFail($id);

        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $this->data->model = new Permission;
        $this->data->model->title = $request->title;
        $this->data->model->parent_id = $request->id;
        $this->data->model->save();

        return parent::create($request, $locale);
    }

    public function update(Request $request, $locale, $id)
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

    public function delete($locale, $id)
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
