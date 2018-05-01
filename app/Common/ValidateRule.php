<?php

namespace App\Common;
use Validator;

use Illuminate\Auth\Authenticatable;

class ValidateRule
{

    static function validate($input)
    {
        $rules = [
            'name' => 'required|unique:products',
            'price' => 'required',
            'status' => 'required',
            'unit' => 'required',
            'origin' => 'required',
            'describe' => 'required'
        ];
        $messages = [
            'required' => '不能为空',
            'unique' => '不能重复',
        ];

        return Validator::make($input, $rules, $messages);
    }


}
