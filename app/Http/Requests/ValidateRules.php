<?php

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
