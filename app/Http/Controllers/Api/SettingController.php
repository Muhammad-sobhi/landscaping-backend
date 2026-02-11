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
        // 1. Handle File Uploads (Main Logo, About Main, About Extra, and NEW: Partners)
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }

        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'about_image_path'], ['value' => $path]);
        }

        if ($request->hasFile('about_extra_image')) {
            $path = $request->file('about_extra_image')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'about_extra_image_path'], ['value' => $path]);
        }

        // NEW: Handle Partner Logo Upload (Appends to a JSON array)
        if ($request->hasFile('partner_logo')) {
            $path = $request->file('partner_logo')->store('partners', 'public');
            
            $existingRecord = Setting::where('key', 'partner_logos')->first();
            $logos = $existingRecord ? json_decode($existingRecord->value, true) : [];
            
            // Add new logo entry
            $logos[] = [
                'id' => uniqid(),
                'path' => $path,
                'name' => $request->input('name', 'Partner Logo')
            ];

            Setting::updateOrCreate(
                ['key' => 'partner_logos'], 
                ['value' => json_encode($logos)]
            );
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
                    'about_extra_preview',
                    'about_extra_file',    
                    'about_extra_image_path',
                    'partner_logos' // Added to protected to handle via specialized logic
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

    // Helper for public site
    public function getLandingPageData() {
        return response()->json([
            'settings' => Setting::pluck('value', 'key'),
            'services' => \App\Models\Service::all(),
            'portfolio' => \App\Models\PortfolioItem::all(),
            'testimonials' => \App\Models\Testimonial::where('status', 'approved')->get(),
        ]);
    }
}
