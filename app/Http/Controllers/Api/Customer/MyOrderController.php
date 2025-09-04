<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class MyOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api'
        ];
    }

    public function index()
    {
        $transactions = Transaction::query()
        ->with('customer')
        ->where('customer_id', auth()->guard('api')->user()->id)
        ->latest()
        ->paginate(5);

        return response()->json([
            'success'   => true,            
            'message'   => 'list Pesanan: ' . auth()->guard('api')->user()->name,
            'data'      => $transactions
        ]);
    }

    public function show($snap_token)
    {
        $transactions = Transaction::query()
            ->with('customer', 'shipping', 'transactionDetails.product')
            ->where('customer_id', auth()->guard('api')->user()->id)
            ->where('snap_token', $snap_token)
            ->firstOrFail();

        return response()->json([
            'success'   => true,
            'message'   => 'Detail Pesanan',
            'data'      => $transactions
        ]);
    }
}
