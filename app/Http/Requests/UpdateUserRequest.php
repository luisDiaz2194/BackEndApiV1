<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
      //dd($this->route('directorio')->id);
        return [
            'nombres' => 'required|min:5|max:100',
            'identificacion' => 'numeric|required|unique:users,identificacion,'.$this->route('user')->id,
            'telefono' => 'numeric|required|unique:users,telefono,'.$this->route('user')->id,
            'email' => 'numeric|email|required|unique:users,email,'.$this->route('user')->id
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'Debe proveer un nombre para esta entrada',
            'identificacion.required' => 'Debe agregar un numero unico',
            'telefono.required' => 'Debe agregar un numero unico',
        ];
    }
}
