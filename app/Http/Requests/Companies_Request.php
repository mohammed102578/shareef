<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Companies_Request extends FormRequest
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
            
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'vat_number' => ['integer'],
            'companies_id' => ['required','integer'],
            'app_id' => ['required','integer'],
            
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'الاسم مطلوب.',
            'address.required' => 'العنوان مطلوب.',
            'company_name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'vat_number.integer'=>'يجب ان يحتوي الرقم الضريبي على ارقام  فقط',
            'companies_id.integer'=>'يجب ان يحتوي الرقم الضريبي على ارقام  فقط',
            'companies_id.required' => ' رقم الشركة مطلوب.',
            'app_id.required' => ' رقم التطبيق مطلوب.',
            'app_id.integer'=>'يجب ان يحتوي الرقم الضريبي على ارقام  فقط',


        ];
    }
}
