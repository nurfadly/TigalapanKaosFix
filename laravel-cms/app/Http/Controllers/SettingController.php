<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::current();

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'address_city' => ['nullable', 'string', 'max:100'],
            'address_province' => ['nullable', 'string', 'max:100'],
            'footer_description' => ['nullable', 'string', 'max:500'],
            // Google Analytics 4 (opsional, diisi saat website sudah live).
            'ga4_property_id' => ['nullable', 'string', 'max:100'],
            'ga4_credentials_path' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::current()->update($data);

        return redirect()->route('settings.edit')->with('status', 'Pengaturan berhasil disimpan.');
    }
}
