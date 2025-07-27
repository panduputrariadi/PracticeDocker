<?php

namespace App\Http\Controllers;

use App\Services\VehicleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    protected $vehicleService;
    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function index(Request $request) {
        $action = $request->input('action');

        switch ($action) {
            case 'all':
                return $this->vehicleService->getAllVehicles($request);
                break;
            case 'soft-delete':
                return $this->vehicleService->getSoftDeleteVehicles($request);
                break;
            case 'restore':
                return $this->vehicleService->restoreVehicle($request->id);
                break;
            case 'force-delete':
                return $this->vehicleService->forceDeleteVehicle($request->id);
                break;
            case 'fetch-brand':
                return $this->vehicleService->fetchVehicleByBrand();
                break;
            case 'fetch-model':
                return $this->vehicleService->fetchVehicleByModel();
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action parameter'
                ], Response::HTTP_BAD_REQUEST);
                break;
        }
    }

    public function store(Request $request) {
        $action = $request->input('action');

        switch ($action) {
            case 'create':
                return $this->vehicleService->createVehicle($request);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action parameter'
                ], Response::HTTP_BAD_REQUEST);
                break;
        }
    }

    public function update(Request $request, $id) {
        $action = $request->input('action');

        switch ($action) {
            case 'update':
                return $this->vehicleService->updateData($request, $id);
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
