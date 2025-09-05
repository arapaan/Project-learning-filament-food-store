<?php

namespace App\Http\Controllers\Api\Public;

use Midtrans\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Controllers\HasMiddleware;
use Midtrans\Snap;

class CheckOutController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api'
        ];
    }

    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        DB::transaction(function() use ($request) {
            $invoice = 'INV-' . mt_rand(1000, 9999);

            $transaction = Transaction::create([
                'customer_id'       => auth()->guard('api')->user()->id,
                'invoice'           => $invoice,
                'province_name'     => $request->province_name,
                'city_name'         => $request->city_name,
                'district_name'     => $request->district_name,
                'subdistrict_name'  => $request->subdistrict_name,
                'zip_code'          => $request->zip_code,
                'full_address'      => $request->full_address,
                'weight'            => $request->weight,
                'total'             => $request->total,
                'status'            => 'PENDING',
            ]);

            $transaction->shipping()->create([
                'transaction_id'    => $transaction->id,
                'shipping_courier'  => $request->shipping_courier,
                'shipping_service'  => $request->shipping_service,
                'shipping_cost'     => $request->shipping_cost
            ]);

            $item_details = [];

            foreach (Cart::where('customer_id', auth()->guard('api')->user()->id)->get() as $cart) {
                $transaction->transactionDetails()->create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $cart->product->id,
                    'price'          => $cart->product->price * $cart->qty,
                    'quantity'       => $cart->qty,
                ]);
            
                $item_details[] = [
                    'id'       => $cart->product->id,
                    'price'    => $cart->product->price,
                    'quantity' => $cart->qty,
                    'name'     => $cart->product->title,
                ];
            }
            
            // Item detail shipping
            $item_details[] = [
                'id'       => 'SHIPPING',
                'price'    => $request->shipping_cost,
                'quantity' => 1,
                'name'     => 'Shipping Cost',
            ];
            
            // Remove cart by customer
            Cart::where('customer_id', auth()->guard('api')->user()->id)->delete();
            
            // Buat payload ke Midtrans kemudian simpan snap tokennya
            $payload = [
                'transaction_details' => [
                    'order_id'     => $transaction->invoice,
                    'gross_amount' => $transaction->grand_total,
                ],
                'customer_details' => [
                    'first_name' => auth()->guard('api')->user()->name,
                    'email'      => auth()->guard('api')->user()->email,
                    'address'    => $transaction->address,
                ],
                'item_details' => $item_details,
            ];
            
            // Create Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            
            $transaction->snap_token = $snapToken;
            $transaction->save();
            
            $this->response['snap_token'] = $snapToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil!',
                'data'    => $this->response,
            ]);
        });
    }
}
