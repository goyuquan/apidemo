<?php
namespace App\Http\Requests;

class ValidateRules extends Request
{
    public function rules()
    {
        return [
            'name' => 'required|unique:products',
            'price' => 'required',
            'status' => 'required',
            'unit' => 'required',
            'origin' => 'required',
            'describe' => 'required'
        ];
    }
}
