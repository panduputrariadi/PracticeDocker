<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryService{
    public function allCategories(Request $request)
    {
        try {
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
            $categories = Category::withoutTrashed()->paginate($perPage, ['*'], 'page', $page);

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => [
                    'items' => $categories->items(),
                    'meta' => [
                        'current_page' => $categories->currentPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                        'last_page' => $categories->lastPage(),
                        'from' => $categories->firstItem(),
                        'to' => $categories->lastItem()
                    ]
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCategory($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully',
                'data' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'description' => 'required|string',
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
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $category = Category::create([
                'slug' => $slug,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed to update category',
            'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
        ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function softDeleteCategory($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category soft deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSoftDeletedCategories(Request $request)
    {
        try {
            // Konfigurasi pagination
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

            // Query dengan pagination
            $categories = Category::onlyTrashed()->paginate($perPage, ['*'], 'page', $page);

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Soft deleted categories retrieved successfully',
                'data' => [
                    'items' => $categories->items(),
                    'meta' => [
                        'current_page' => $categories->currentPage(),
                        'total' => $categories->total(),
                        'per_page' => $categories->perPage(),
                        'last_page' => $categories->lastPage(),
                        'from' => $categories->firstItem(),
                        'to' => $categories->lastItem(),
                    ],
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve soft deleted categories',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCategory($id)
    {
        try {
            $category = Category::withTrashed()->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            if (!$category->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category is not deleted',
                ], Response::HTTP_BAD_REQUEST);
            }

            $category->restore();

            return response()->json([
                'success' => true,
                'message' => 'Category restored successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDeleteCategory($id)
    {
        try {
            $category = Category::withTrashed()->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $category->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Category permanently deleted',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete category',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function dropDownCategory(Request $request)
    {
        try {
            $query = Category::select('id', 'name')->limit(5);
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('name', 'ilike', '%' . $search . '%');
            }
            $categories = $query->get()->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

