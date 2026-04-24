<?php
//
//namespace App\Http\Requests;
//
//use Illuminate\Contracts\Validation\ValidationRule;
//use Illuminate\Foundation\Http\FormRequest;
//
//class CategoryFormRequest extends FormRequest
//{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return true;
//    }
//
//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array<string, ValidationRule|array<mixed>|string>
//     */
//    public function rules(): array
//    {
//        return [
//            'name' => [
//                'required',
//                'string',
//                'min:3',
//            ]
//        ];
//    }
//
//    public function messages()
//    {
//        return [
//            'name.required' => "É obrigatório informar um nome da categoria.",
//            'name.min' => "O nome da categoria deve ter no mínimo 3 caracteres."
//        ];
//    }
//}
