<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Http, Cache};
use Illuminate\View\View;

class SettingController extends Controller
{
    // Clave para gestionar la persistencia de la configuración en memoria
    private string $cacheKey = 'app_settings';

    public function index(): View
    {
        return view('settings.index');
    }

    // Procesa la actualización masiva de parámetros del sistema
    public function update(Request $request): RedirectResponse
    {
        // Limpiamos los campos de control de Laravel
        $settings = $request->except(['_token', '_method']);

        // Estructuramos el payload para un upsert atómico (alto rendimiento)
        $payload = collect($settings)->map(fn($value, $key) => [
            'key'   => $key,
            'value' => $value ?? ''
        ])->values()->all();

        if (!empty($payload)) {
            Setting::upsert($payload, ['key'], ['value']);
        }

        // Invalidamos la caché para forzar la lectura de los nuevos valores
        Cache::forget($this->cacheKey);

        return back()->with('success', 'Configuraciones actualizadas correctamente.');
    }

    // Sincroniza la tasa cambiaria contra el proveedor externo
    public function sync(): RedirectResponse
    {
        try {
            // Se define un timeout corto para no bloquear el hilo del servidor
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0'
                ])
                ->timeout(8)
                ->get('https://api.dolarvzla.com/public/exchange-rate');

            if ($response->successful()) {
                $rate = $response->json()['current']['usd'] ?? null;

                if ($rate) {
                    Setting::updateOrCreate(['key' => 'bcv_rate'], ['value' => $rate]);
                    Cache::forget($this->cacheKey);

                    return back()->with('success', "Tasa sincronizada: Bs. " . number_format($rate, 2));
                }
            }

            return back()->with('error', 'El proveedor de datos no devolvió una tasa válida.');

        } catch (\Exception $e) {
            return back()->with('error', 'Fallo de conexión con el servicio.');
        }
    }
}