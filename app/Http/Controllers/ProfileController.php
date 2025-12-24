<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Redirect};
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Carga la interfaz de edición de perfil con la data del usuario autenticado
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // Actualiza la información del perfil del usuario autenticado
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Si el email ha cambiado, reiniciamos la verificación
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return to_route('profile.edit')->with('status', 'profile-updated');
    }

    // Elimina la cuenta del usuario autenticado tras validar la contraseña
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Cierre de sesión 
        Auth::logout();
        $user->delete();

        // Invalida la sesión actual y regenera el token CSRF por seguridad
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
