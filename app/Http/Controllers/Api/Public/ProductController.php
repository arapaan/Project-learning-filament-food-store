<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with('category', 'ratings.customer')
            ->withAvg('ratings', 'rating')
            ->when(request()->has('search'), function ($query) {
                $query->where('title', 'like', '%' . request()->search . '%');
            })
            ->paginate(5);

        $products->getCollection()->transform(function ($product) {
            $product->ratings_avg_rating = number_format($product->ratings_avg_rating,1);
            return $product;
        });

        return response()->json([
            'success'   => true,
            'message'   => 'List Produk',
            'data'      => $products
        ]);
    }

    public function productPopular()
    {
        $products = Product::query()
            ->with('category', 'ratings.customer')
            ->withAvg('ratings', 'rating')
            ->withCount(['ratings' => function ($query) {
                $query->where('rating', '>-', 4);
            }])
            ->when(request()->has('search'), function ($query) {
                $query->where('title', 'like', '%' . request()->search . '%');
            })
            ->orderBy('ratings_count', 'desc')
            ->limit(5)
            ->get();

        $products->transform(function ($product) {
            $product->ratings_avg_rating = number_format($product->ratings_avg_rating, 1);
            return $product;
        });

        return response()->json([
            'success'   => true,
            'message'   => 'List Produk Terpopuler',
            'data'      => $products
        ]);
    }

    public function show($slug)
    {
        $product = Product::query()
            ->with('category', 'ratings.customer')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        $product->ratings_avg_rating = number_format($product->ratings_avg_rating, 1);

        return response()->json([
            'success'   => true,
            'message'   => 'Detail Produk',
            'data'      => $product
        ]);
    }
}
