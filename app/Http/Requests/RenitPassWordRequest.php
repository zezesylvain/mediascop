<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenitPassWordRequest extends FormRequest
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
            'ancien_mot_de_passe' => 'required|min:6',
            'nouveau_mot_de_passe' => 'required|min:6',
            'password' => 'required|min:6',
        ];
    }
}
