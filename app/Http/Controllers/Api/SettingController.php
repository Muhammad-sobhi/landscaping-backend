<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Fetch current settings
    public function index()
    {
        return response()->json(Setting::pluck('value', 'key'));
    }

    // Save or Update settings (Project Name and Tax)
    public function update(Request $request)
    {
        // 1. Handle File Uploads (these are usually sent at the top level of FormData)
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }
    
        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'about_image_path'], ['value' => $path]);
        }
    
        // 2. Handle the nested settings object
        // We check if 'settings' exists in the request
        if ($request->has('settings')) {
            $settingsData = $request->input('settings');
            
            // If it's a string (sometimes happens with FormData), decode it
            if (is_string($settingsData)) {
                $settingsData = json_decode($settingsData, true);
            }
    
            foreach ($settingsData as $key => $value) {
                if ($key === 'about_image_preview' || $key === 'about_image_file' || $key === 'about_image_path') {
                    continue;
                }
                $finalValue = is_array($value) ? json_encode($value) : $value;

                Setting::updateOrCreate(
                    ['key' => $key], 
                    ['value' => $finalValue] // Fixed: use $finalValue here too
                );
            }
        }
    
        return response()->json(['message' => 'Settings updated successfully']);
    }
}
