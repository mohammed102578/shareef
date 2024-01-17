<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store_Request extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'product_id.required'=>'اسم المنتج مطلوبة',
            'product_id.exists'=>'اسم المنتج غير موجود', 
            
           
        ];
    }
}
