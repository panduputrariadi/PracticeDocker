<?php

namespace App\Http\Controllers;

use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    protected $brandService;
    public function __construct(BrandService $brandService) {
        $this->brandService = $brandService;
    }
    public function index(Request $request) {
        $action = $request->input('action');

        switch ($action) {
            case 'all':
                return $this->brandService->getAllBrands($request);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action parameter'
                ], Response::HTTP_BAD_REQUEST);
                break;
        }
    }
}
