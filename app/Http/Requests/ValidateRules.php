<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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

    public function message()
    {
      return [
        'name.required' => 'A name is required',
        'color.required' => 'A color is required',
        'image.required' => 'An image is required'
      ];
    }
}
