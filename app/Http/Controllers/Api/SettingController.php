<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::pluck('value', 'key'));
    }

    public function store(Request $request) {
        $key = $request->input('key');
        $value = $request->input('value');

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return response()->json($setting);
    }

    public function update(Request $request)
    {
        // 1. Handle File Uploads (Main Logo, About Main, and About Extra)
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }

        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'about_image_path'], ['value' => $path]);
        }

        // NEW: Handle the Extra About Image
        if ($request->hasFile('about_extra_image')) {
            $path = $request->file('about_extra_image')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'about_extra_image_path'], ['value' => $path]);
        }

        // 2. Handle the nested settings object
        if ($request->has('settings')) {
            $settingsData = $request->input('settings');
            
            if (is_string($settingsData)) {
                $settingsData = json_decode($settingsData, true);
            }

            foreach ($settingsData as $key => $value) {
                // PROTECTED KEYS: These must match exactly what you delete in React
                $protectedKeys = [
                    'about_image_preview', 
                    'about_image_file', 
                    'about_image_path', 
                    'logo_path',
                    'about_extra_preview', // NEW
                    'about_extra_file',    // NEW
                    'about_extra_image_path' // NEW
                ];

                if (in_array($key, $protectedKeys)) continue;

                $finalValue = is_array($value) ? json_encode($value) : $value;
                
                Setting::updateOrCreate(
                    ['key' => $key], 
                    ['value' => $finalValue] 
                );
            }
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }
}
