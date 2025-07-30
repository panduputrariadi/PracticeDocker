<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Http\Response;

class BrandService
{
    public function getAllBrands()
    {
        $brands = Brand::select('id', 'brand_name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Brands retrieved successfully',
            'data' => $brands
        ], Response::HTTP_OK);
    }
}
