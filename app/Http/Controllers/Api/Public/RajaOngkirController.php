<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function searchDestination(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $request->search,
            'limit'  => 100,
            'offset' => 0
        ]);

        return response()->json($response['data']);
    }

    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin'        => 46174,
            'destination'   => $request->destination,
            'weight'        => $request->weight,
            'courier'       => $request->courier
        ]);

        return response()->json($response['data']);
    }
}
