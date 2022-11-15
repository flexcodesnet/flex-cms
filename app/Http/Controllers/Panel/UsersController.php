<?php

namespace App\Http\Controllers\Panel;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends PanelController
{
    public function __construct()
    {
        parent::__construct();
        $this->data->slug = 'users';
        $this->data->class = User::class;
        $this->data->ths = ['messages.fields.name'];
        if (auth()->user() == null) abort(401);
        $this->data->fields = [
            [
                'slug' => 'name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'slug' => 'username',
                'type' => 'text',
                'required' => false,
                'disabled' => true,
            ],
            [
                'slug' => 'email',
                'type' => 'email',
                'required' => true,
            ],
            [
                'slug' => 'password',
                'type' => 'password',
                'required' => false,
            ],
            [
                'slug' => 'password_confirmation',
                'type' => 'password',
                'required' => false,
            ],
            [
                'slug' => 'roles',
                'relation_key' => 'role_id',
                'type' => 'select',
                'required' => true,
                'query' => Role::query()->where('id', '>=', auth()->user()->role_id),
            ],
        ];
    }

    public function data(Request $request)
    {
        $this->data->result = User::query()->where('role_id', '>=', auth()->user()->role_id)->get();
        return parent::data($request);
    }

    public function show($locale, $id)
    {
        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->with('role')->findOrFail($id);
        return parent::show($locale, $id);
    }

    public function edit($locale, $id)
    {
        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->with('role')->findOrFail($id);
        return parent::edit($locale, $id);
    }

    public function create(Request $request, $locale, $id = null)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        if (!($request->role_id >= auth()->user()->role_id))
            abort(500);

        $this->data->model = new User;
        $this->data->model->name = $request->name;
        $this->data->model->username = $request->name;
        $this->data->model->email = $request->email;
        $this->data->model->role_id = $request->role_id;
        $this->data->model->password = $request->password;
        $this->data->model->save();
        return parent::create($request, $locale);
    }

    public function update(Request $request, $locale, $id)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => 'nullable|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        if (!($request->role_id >= auth()->user()->role_id))
            abort(401);

        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->findOrFail($id);
        $this->data->model->email = $request->email;
        $this->data->model->name = $request->name;
        $this->data->model->username = $request->name;
        $this->data->model->role_id = $request->role_id;
        if (!is_null($request->password))
            $this->data->model->password = $request->password;
        $this->data->model->save();
        return parent::update($request, $locale, $id);
    }
}
