<?php

// app/Http/Controllers/Api/PortfolioItemController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class PortfolioItemController extends Controller
{
    public function index()
    {
       try {
            // We use all() first to test if the table exists at all
            return response()->json(PortfolioItem::all());
        } catch (\Exception $e) {
            // This will return the ACTUAL error message to your browser
            return response()->json([
                'error' => 'Laravel Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->only(['title', 'category', 'is_featured']);

        if ($request->hasFile('image')) {
            // Using your branding/store logic
            $data['image_path'] = $request->file('image')->store('portfolio', 'public');
        }

        $item = PortfolioItem::create($data);
        return response()->json($item);
    }

    // ... inside update method
public function update(Request $request, $id) // Use $id to be safe with apiResource
{
    $portfolioItem = PortfolioItem::findOrFail($id);
    
    $data = $request->only(['title', 'category', 'is_featured']);

    if ($request->hasFile('image')) {
        $data['image_path'] = $request->file('image')->store('portfolio', 'public');
    }

    $portfolioItem->update($data);
    return response()->json($portfolioItem);
}

// ... inside destroy method
public function destroy($id)
{
    $portfolioItem = PortfolioItem::findOrFail($id);
    $portfolioItem->delete();
    return response()->json(['message' => 'Project removed from portfolio']);
}
}