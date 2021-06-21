<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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

  //Reglas de Validación
  public function rules()
  {
      return [
          'nombres' => 'required|min:5|max:100',
          'identificacion' => 'numeric|required|unique:users',
          'telefono' => 'numeric|required|unique:users',
          'email' => 'required|email|unique:users'
      ];
  }

  public function messages()
  {
      return [
          'nombres.required' => 'Debe proveer un nombre para esta entrada',
          'telefono.required' => 'Debe agregar un numero unico',
      ];
  }
}
