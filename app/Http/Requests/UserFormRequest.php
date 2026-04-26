<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
//use Illuminate\Foundation\Http\FormRequest;
//use \Illuminate\Validation\Rules\Password;
//
//class UserFormRequest extends FormRequest
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
//            'username' => [
//                'required',
//                'string',
//                'alpha_dash:ascii',
//                'min:3',
//                'max:20',
//            ],
//            'email' => [
//                'required',
//                'string',
//                'email',
//            ],
//            'password' => [
//                'required',
//                'confirmed',
//                'string',
//                Password::min(8)
//                    ->mixedCase()
//                    ->numbers()
//                    ->symbols()
//                    ->uncompromised(),
//            ]
//        ];
//
//    }
//
//    public function messages()
//    {
//        return [
//            'username.required' => "É obrigatório informar um nome de usuário.",
//            'username.min' => "O nome de usuário deve ter no mínimo 3 caracteres.",
//            'username.alpha_dash' => "O nome de usuário só pode conter caracteres alfanuméricos, underline (_) e hífen (-).",
//            'username.max' => "O nome de usuário deve ter no máximo 20 caracteres.",
////            'username.string' => "O nome de usuário deve conter letras.",
//            'email.required' => "É obrigatório informar um endereço de e-mail.",
//            'email.email' => "O e-mail deve ser válido.",
////            'email.string' => "O e-mail deve conter letras.",
//            'password.required' => "É obrigatório informar uma senha.",
//            'password.confirmed' => "É obrigatório a confirmação da senha.",
////            'password.string' => "",
//            'password.mixed' => 'A senha deve conter no mínimo uma letra maiúscula e uma minúscula.',
//            'password.symbols' => 'A senha deve conter no mínimo um caractere especial.',
//            'password.numbers' => 'A senha deve conter no mínimo um número.',
//            'password.uncompromised' => 'A senha informada está com a segurança comprometida.',
//            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
//        ];
//    }
//}
