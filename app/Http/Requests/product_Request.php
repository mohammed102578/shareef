<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class product_Request extends FormRequest
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
            'product_name' => ['required', 'string', 'max:255','min:2'],
            'packing'=>'required|exists:packings,id',
            
           
        ];
    }

    public function messages()
    {
        return [
            'generic_name.required' => 'الاسم مطلوب.',
           
            'product_name.required' => 'الاسم  العلمي مطلوب.',
            'product_name.min'=>'يجب ان يحتوي  الاسم العلمي على حرفين على الاقل',
            'product_name.string'=>'يجب ان يحتوي الاسم العلمي على الاحرف فقط',
            'packing.required'=>'اسم الوحدة مطلوبة',
            'packing.exists'=>'اسم الوحدة غير موجود'
           
        ];
    }
}
