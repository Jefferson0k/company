<?php

namespace App\Http\Requests\Portal\Registro;

use Illuminate\Foundation\Http\FormRequest;

class RegistroJuridicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'              => ['required', 'string', 'max:200'],
            'apellidos'           => ['required', 'string', 'max:200'],
            'ruc'                 => ['required', 'string', 'max:20', 'unique:clientes,ruc'],
            'departamento'        => ['required', 'string', 'max:100'],
            'email'               => ['required', 'email', 'unique:clientes,email'],
            'password'            => ['required', 'string', 'min:8', 'confirmed'],
            'telefono'            => ['required', 'string', 'max:20'],
            'acepta_terminos'     => ['required', 'accepted'],
            'archivo_comprobante' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'acepta_terminos.accepted'     => 'Debes aceptar los términos y condiciones.',
            'archivo_comprobante.required' => 'Debes subir un archivo.',
            'archivo_comprobante.max'      => 'El archivo no debe superar 5MB.',
        ];
    }
}