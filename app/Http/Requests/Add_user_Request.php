<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Add_user_Request extends FormRequest
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
            'name' => ['required', 'string', 'max:255','min:2'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
            'group'=>['required'],
            
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            
            'name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            'username.required' => 'اسم  المستخدم مطلوب.',
            'password.required' => 'كلمة المرور مطلوب.',
            'name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'username.unique'=>'اسم المستخدم  مستخدم من قبل', 
            'password.min'=>'يجب ان يحتوي كلمة السر على 4 احرف على الاقل',
            'group.required'=>'اسم المجموعة مطلوبة',
           
            'group.exists'=>'اسم المجموعة غير موجود',
            'group'=>'required|exists:groups,id',
            
        ];
    }
}
