<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    public function getAllRentals(Request $request)
    {
        try{
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

            $perPage = $request->input('per_page', 10); // Default 10 item per halaman
            $page = $request->input('page', 1); // Default halaman 1

            $rentals = Rental::with(['car', 'userRental'])->withoutTrashed()->paginate($perPage, ['*'], 'page', $page);

            if($rentals->count() > 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Cars retrieved successfully',
                    'data' => [
                        'items' => $rentals->items(),
                        'meta' => [
                            'current_page' => $rentals->currentPage(),
                            'per_page' => $rentals->perPage(),
                            'total_items' => $rentals->total(),
                            'total_pages' => $rentals->lastPage(),
                            'has_more_pages' => $rentals->hasMorePages(),
                            'next_page_url' => $rentals->nextPageUrl(),
                            'previous_page_url' => $rentals->previousPageUrl()
                        ]
                    ]
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Rental not found',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createRental(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'carId' => 'required|exists:cars,id',
                'rentalStart' => 'required|date|after_or_equal:today',
                'rentalEnd' => 'required|date|after:rentalStart'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $userId = auth()->id();
            $car = Car::findOrFail($request->carId);

            // Validasi status mobil
            if ($car->status === 'RENTED') {
                return response()->json([
                    'message' => 'Mobil sudah disewa'
                ], 409);
            }

            // Hitung jumlah hari sewa
            $start = Carbon::parse($request->rentalStart);
            $end = Carbon::parse($request->rentalEnd);
            $days = $start->diffInDays($end);

            // Hitung total harga
            $totalPrice = $days * $car->price;

            // Buat rental record
            $rental = Rental::create([
                'carId' => $request->carId,
                'userId' => $userId,
                'rentalStart' => $request->rentalStart,
                'rentalEnd' => $request->rentalEnd,
                'totalPayment' => $totalPrice,
                'status' => 'ACTIVE'
            ]);

            // Update status mobil
            $car->update(['status' => 'RENTED']);

            DB::commit();

            return response()->json([
                'message' => 'Sewa berhasil dibuat',
                'data' => $rental,
                'price_details' => [
                    'daily_price' => $car->price,
                    'days' => $days,
                    'total' => $totalPrice
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat sewa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
