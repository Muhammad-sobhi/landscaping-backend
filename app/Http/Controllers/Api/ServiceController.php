<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        return response()->json(Service::orderBy('order', 'asc')->get());
    }

    public function store(Request $request) {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('services', 'public');
        }

        // Tags will be sent as an array from React; Laravel handles JSON casting
        $service = Service::create($data);
        return response()->json($service);
    }

    public function update(Request $request, Service $service) {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);
        return response()->json($service);
    }

    public function destroy(Service $service) {
        $service->delete();
        return response()->json(['message' => 'Deleted']);
    }
}