<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Supplier_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_name' => ['required', 'string', 'max:255','min:2'],
           
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.required' => 'الاسم مطلوب.',
            'supplier_name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            'supplier_name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
           
            
            
        ];
    }
}
