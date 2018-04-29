<?php
namespace App\Http\Requests;
use Illuminate\Http\Request;

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
