<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class RatingController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            'auth:api'
        ];
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $check_rating = Rating::query()
            ->where('product_id', $request->product_id)
            ->where('customer_id', auth()->guard('api')->user()->id)
            ->first();

        if ($check_rating) {
            return response()->json([
                'success'   => false,
                'message'   => 'Anda sudah pernah memberikan rating untuk produk ini',
                'data'      => $check_rating
            ], 409);
        }

        $rating = Rating::create([
            'rating'                => $request->rating,
            'review'                => $request->review,
            'product_id'            => $request->product_id,
            'transaction_detail_id' => $request->transaction_detail_id,
            'customer_id'           => auth()->guard('api')->user()->id
        ]);

        return response()->json([
            'successs'  => true,
            'message'   => 'Rating Berhasil Ditambahkan',
            'data'      => $rating
        ]);
    }
}
