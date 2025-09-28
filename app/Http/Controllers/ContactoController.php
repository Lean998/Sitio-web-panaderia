<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function submit(Request $request)
    {
        // Mensajes personalizados en español
        $messages = [
            'nombre.required' => 'Por favor ingresa tu nombre.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'Por favor ingresa un correo electrónico válido.',
            'email.max' => 'El correo electrónico es demasiado largo.',
            'mensaje.required' => 'Por favor escribe un mensaje.',
            'mensaje.min' => 'El mensaje debe tener al menos 10 caracteres.',
            'mensaje.max' => 'El mensaje no puede tener más de 1000 caracteres.',
        ];

        // Validación con mensajes personalizados
        $validated = $request->validate([
            'nombre' => 'required|min:2|max:100',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|min:10|max:1000',
        ], $messages);

        // Aquí se procesara el formulario (enviar email o guardar en BD)
        
        return back()->with('success', '¡Mensaje enviado correctamente! Te responderemos pronto.');
    }

    public function show()
    {
        return view('contacto'); 
    }
}