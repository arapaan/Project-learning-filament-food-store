<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Kategori',
            'data'    => $categories
        ]);
    }

    public function show($slug)
    {
        $category = Category::query()
        ->with('products')
        ->where('slug', $slug)
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'List Produk Kategori' . $category->name,
            'data'    => $category
        ]);
    }
}
