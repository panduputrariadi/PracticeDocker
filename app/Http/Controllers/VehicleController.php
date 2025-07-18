<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    public function getAllVehicles(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validation->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Konfigurasi pagination
            $perPage = $request->input('per_page', 10); // Default 10 item per halaman
            $page = $request->input('page', 1); // Default halaman 1

            // Query dengan pagination
            $vehicles = Vehicle::withoutTrashed()
                ->with(['category', 'images' => function ($query) {
                    $query->select('vehicleId', 'imagePath');
                }])
                ->paginate($perPage, ['*'], 'page', $page);

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Vehicles retrieved successfully',
                'data' => [
                    'items' => $vehicles->items(),
                    'meta' => [
                        'current_page' => $vehicles->currentPage(),
                        'total' => $vehicles->total(),
                        'per_page' => $vehicles->perPage(),
                        'last_page' => $vehicles->lastPage(),
                        'from' => $vehicles->firstItem(),
                        'to' => $vehicles->lastItem(),
                    ]
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createVehicle(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|string|max:50',
                'transmission' => 'required|string|max:50',
                'plate_number' => 'required|string|max:20|unique:vehicles',
                'fuel_type' => 'required|string|max:50',
                'color' => 'required|string|max:50',
                'rate_per_day' => 'required|numeric|min:0',
                'rate_per_hour' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:0',
                'images' => 'required|array|min:1',
                'images.*' => 'required|string',
                'mileage' => 'required|numeric|min:0',
                'model' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'year' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Vehicle::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            // handle upload image
            if($request->hasFile('images')) {
                $path_image = storage_path('app/public/vehicles/'.$vehicle->slug);
                if(!File::exists($path_image)) {
                    File::makeDirectory($path_image, 0777, true);
                }

                foreach($request->file('images') as $image) {
                    try{
                        $path = $image->store('vehicles/'.$vehicle->slug);
                        $vehicleImageUrl = asset(Storage::url($path));
                        VehicleImage::create([
                            'vehicle_id' => $vehicle->id,
                            'imagePath' => $vehicleImageUrl,
                            'imageType' => $image->getClientOriginalExtension(),
                            'originalName' => $image->getClientOriginalName()
                        ]);
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => $e->getMessage()
                        ], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            }

            $vehicle = Vehicle::create([
                'slug' => $slug,
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'status' => $request->status,
                'transmission' => Vehicle::TRANSMISSION_MANUAL,
                'plate_number' => $request->plate_number,
                'fuel_type' => $request->fuel_type,
                'color' => $request->color,
                'rate_per_day' => $request->rate_per_day,
                'rate_per_hour' => $request->rate_per_hour,
                'capacity' => $request->capacity,
                'mileage' => $request->mileage,
                'model' => $request->model,
                'brand' => $request->brand,
                'type' => $request->type,
                'year' => $request->year
            ]);

            DB::commit();
            $vehicle->load('images');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'data' => $vehicle
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
