<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class CartController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api'
        ];
    }

    public static function index()
    {
        $carts = Cart::query()
            ->with('product')
            ->where('customer_id', auth()->guard('api')->user()->id)
            ->latest()
            ->get();

        $totalWeight = $carts->sum(function ($cart) {
            return $cart->product->weight * $cart->qty;
        });

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->qty;
        });

        return response()->json([
            'success'   => true,
            'message'   => 'List Keranjangg' . auth()->guard('api')->user()->name,
            'data'      => [
                'total_weight'  => $totalWeight,
                'total_price'   => $totalPrice,
                'carts'         => $carts
            ]
        ]);
    }

    public function store(Request $request)
    {
        $item = Cart::where('product_id', $request->product_id)
            ->where('customer_id', auth()->guard('api')->user()->id)
            ->first();

        if ($item) {
            $item->increment('qty');
        } else {
            $item = Cart::create([
                'customer_id'   => auth()->guard('api')->user()->id,
                'product_id'    => $request->product_id,
                'qty'           => $request->qty ?? 1
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Produk ditambahkan ke keranjang',
            'data'      => $item
        ]);
    }

    public function IncrementCart(Request $request)
    {
        $item = Cart::where('product_id', $request->product_id)
            ->where('customer_id', auth()->guard('api')->user()->id)
            ->where('id', $request->cart_id)
            ->firts();

        if ($item) {
            $item->increment('qty');
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Qty Keranjang Berhasil Ditambahkan',
            'data'      => $item
        ]);
    }

    public function DecrementCart(Request $request)
    {
        $item = Cart::where('product_id', $request->product_id)
        ->where('customer_id', auth()->guard('api')->user()->id)
        ->where('id', $request->cart_id)
        ->firts();

        if ($item) {
            $item->decrement('qty');
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Qty Keranjang Berhasil Dikurangi',
            'data'      => $item
        ]);
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'Keranjang Berhasil Dihapus',
            'data'      => $cart
        ]);
    }
}
