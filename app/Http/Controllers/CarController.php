<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function createCar(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'status' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'color' => 'required|string|max:50',
                'type' => 'required|string|max:50',
                'description' => 'required|string',
                'plateNumber' => 'required|string|max:20|unique:cars',
                'images' => 'sometimes|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Buat mobil
            $car = Car::create($request->only([
                'brand', 'model', 'year', 'status', 'price',
                'color', 'type', 'description', 'plateNumber'
            ]));

            // Handle upload gambar (jika ada)
            if ($request->hasFile('images')) {
                $storagePath = storage_path('app/public/car_images');
                if (!File::exists($storagePath)) {
                    File::makeDirectory($storagePath, 0755, true);
                }
                foreach ($request->file('images') as $image) {
                    try {
                        $path = $image->store('car_images', 'public');
                        $imageUrl = asset(Storage::url($path));

                        // Simpan gambar ke database
                        CarImage::create([
                            'carId' => $car->id,
                            'imagePath' => $imageUrl,
                            'originalName' => $image->getClientOriginalName(),
                            'imageType' => $image->getClientMimeType()
                        ]);
                    } catch (\Exception $e) {
                        // Jika gagal menyimpan gambar, lempar exception untuk rollback
                        throw new \Exception("Failed to save car image: " . $e->getMessage());
                    }
                }
            }

            DB::commit();

            $car->load('images');

            return response()->json([
                'success' => true,
                'message' => 'Car created successfully',
                'data' => $car
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error

            Log::error('Error creating car: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create car',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllCars(Request $request)
    {
        try {
            // Validasi input pagination
            $validator = Validator::make($request->all(), [
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Konfigurasi pagination
            $perPage = $request->input('per_page', 10); // Default 10 item per halaman
            $page = $request->input('page', 1); // Default halaman 1


            // Query dengan pagination
            $cars = Car::with(['images' => function ($query) {
                        $query->select('carId', 'imagePath');
                    }])
                    ->withoutTrashed()
                    ->paginate($perPage, ['*'], 'page', $page);

            // Format response
            if($cars->count() > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cars retrieved successfully',
                    'data' => [
                        'items' => $cars->items(),
                        'meta' => [
                            'current_page' => $cars->currentPage(),
                            'per_page' => $cars->perPage(),
                            'total_items' => $cars->total(),
                            'total_pages' => $cars->lastPage(),
                            'has_more_pages' => $cars->hasMorePages(),
                            'next_page_url' => $cars->nextPageUrl(),
                            'previous_page_url' => $cars->previousPageUrl()
                        ]
                    ]
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No cars found',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cars',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editCar(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Temukan mobil yang akan diupdate
            $car = Car::find($id);
            if(!$car) {
                return response()->json([
                    'success' => false,
                    'message' => 'Car not found',
                ], Response::HTTP_NOT_FOUND);
            }
            // Validasi input
            $validator = Validator::make($request->all(), [
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'status' => 'nullable|string|max:50',
                'price' => 'nullable|numeric|min:0',
                'color' => 'nullable|string|max:50',
                'type' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'plateNumber' => 'nullable|string|max:20|unique:cars,plateNumber,' . $id,
                'images' => 'sometimes|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'deleted_images' => 'sometimes|array', // Untuk gambar yang akan dihapus
                'deleted_images.*' => 'exists:car_images,id,carId,' . $id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // Log::info('Request data: ', $request->all());

            //Update data mobil
            $car->update($request->only([
                'brand', 'model', 'year', 'status', 'price',
                'color', 'type', 'description', 'plateNumber'
            ]));


            // // Handle penghapusan gambar
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    $image = CarImage::where('carId', $car->id)->findOrFail($imageId);

                    // Hapus file dari storage
                    Storage::disk('public')->delete($image->imagePath);

                    // Hapus record dari database
                    $image->delete();
                }
            }

            // // Handle upload gambar baru
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('car_images', 'public');
                    $imageUrl = asset(Storage::url($path));

                    CarImage::create([
                        'carId' => $car->id,
                        'imagePath' => $imageUrl,
                        'originalName' => $image->getClientOriginalName(),
                        'imageType' => $image->getClientMimeType()
                    ]);
                }
            }

            DB::commit();

            // Reload data terbaru
            $car->refresh()->load('images');
            Log::info('Car updated:', $request->all() ?: []);
            Log::info('Files received:', $request->file() ?: []);

            return response()->json([
                'success' => true,
                'message' => 'Car updated successfully',
                'data' => $car
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating car: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update car',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSpesificCar($id)
    {
        try {
            $car = Car::with('images')->find($id);
            if(!$car) {
                return response()->json([
                    'success' => false,
                    'message' => 'Car not found',
                ], Response::HTTP_NOT_FOUND);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Car retrieved successfully',
                    'data' => $car
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve car',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCar($id)
    {
        try {
            $car = Car::find($id);

            if (!$car) {
                return response()->json([
                    'success' => false,
                    'message' => 'Car not found',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($car->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Car has already been deleted',
                ], Response::HTTP_GONE);
            }

            $car->delete();

            return response()->json([
                'success' => true,
                'message' => 'Car soft deleted successfully',
                'deleted_at' => $car->deleted_at
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete car',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCar($id)
    {
        $car = Car::withTrashed()->find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$car->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Car is not deleted',
            ], Response::HTTP_BAD_REQUEST);
        }

        $car->restore();

        return response()->json([
            'success' => true,
            'message' => 'Car restored successfully',
        ], Response::HTTP_OK);
    }

    public function forceDeleteCar($id)
    {
        $car = Car::withTrashed()->find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $car->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Car permanently deleted',
        ], Response::HTTP_OK);
    }

    public function getSofDeletedCar(Request $request)
    {
        try {
            // Validasi input pagination
            $validator = Validator::make($request->all(), [
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Konfigurasi pagination
            $perPage = $request->input('per_page', 10); // Default 10 item per halaman
            $page = $request->input('page', 1); // Default halaman 1


            // Query dengan pagination
            $cars = Car::with(['images' => function ($query) {
                        $query->select('carId', 'imagePath');
                    }])
                    ->onlyTrashed()
                    ->paginate($perPage, ['*'], 'page', $page);

            // Format response
            if($cars->count() > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cars retrieved successfully',
                    'data' => [
                        'items' => $cars->items(),
                        'meta' => [
                            'current_page' => $cars->currentPage(),
                            'per_page' => $cars->perPage(),
                            'total_items' => $cars->total(),
                            'total_pages' => $cars->lastPage(),
                            'has_more_pages' => $cars->hasMorePages(),
                            'next_page_url' => $cars->nextPageUrl(),
                            'previous_page_url' => $cars->previousPageUrl()
                        ]
                    ]
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No cars found',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cars',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
