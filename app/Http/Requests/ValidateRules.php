<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ValidateRules extends FormRequest
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
