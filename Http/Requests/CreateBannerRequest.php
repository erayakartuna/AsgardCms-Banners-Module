<?php namespace Modules\Banners\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBannerRequest extends FormRequest{

    public function rules()
    {
        return [];
    }

    public function translationRules()
    {
        return [
            'title' => 'required'
        ];
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