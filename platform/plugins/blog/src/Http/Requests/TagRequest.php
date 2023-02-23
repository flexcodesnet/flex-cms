<?php

namespace FXC\Blog\Http\Requests;

use FXC\Base\Enums\BaseStatusEnum;
use FXC\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TagRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
