<?php

namespace App\Http\Controllers\Api\Public;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $silders = Slider::latest()->get();

        return response()->json([
            'success'   => true,
            'message'   => 'List Slider',            
            'data'      => $silders
        ]);
    }
}
