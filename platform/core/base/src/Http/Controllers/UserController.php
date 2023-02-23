<?php


namespace FXC\Base\Http\Controllers;

use FXC\Base\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function abort;
use function auth;

class UserController extends PanelController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDataFields(User::class);
    }

    /**
     * @param  Request  $request
     * @return void
     * @throws \Exception
     */
    public function data(Request $request)
    {
        $this->data->result = User::query()->where('role_id', '>=', auth()->user()->role_id)->get();
        return parent::data($request);
    }

    /**
     * @param $locale
     * @param $id
     * @return Application|Factory|View
     */
    public function show($locale, $id)
    {
        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->with('role')->findOrFail($id);
        return parent::show($locale, $id);
    }

    /**
     * @param $locale
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($locale, $id)
    {
        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->with('role')->findOrFail($id);
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
        $request->validate([
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        if (!($request->role_id >= auth()->user()->role_id)) {
            abort(500);
        }

        $this->data->model = new User;
        $this->data->model->name = $request->name;
        $this->data->model->username = $request->name;
        $this->data->model->email = $request->email;
        $this->data->model->role_id = $request->role_id;
        $this->data->model->password = $request->password;
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
        $request->validate([
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => 'nullable|min:8|confirmed',
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        if (!($request->role_id >= auth()->user()->role_id)) {
            abort(401);
        }

        $this->data->model = User::query()->where('role_id', '>=', auth()->user()->role_id)->findOrFail($id);
        $this->data->model->email = $request->email;
        $this->data->model->name = $request->name;
        $this->data->model->username = $request->name;
        $this->data->model->role_id = $request->role_id;
        if (!is_null($request->password)) {
            $this->data->model->password = $request->password;
        }
        $this->data->model->save();
        return parent::update($request, $locale, $id);
    }
}
