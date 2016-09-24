<?php namespace Modules\Banners\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest{

    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required'
        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}