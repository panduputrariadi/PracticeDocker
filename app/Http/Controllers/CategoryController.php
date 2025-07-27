<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'all':
                return $this->categoryService->allCategories($request);
                break;
            case 'fetch':
                return $this->categoryService->fetchCategories($request);
                break;
            case 'get-soft-deleted':
                return $this->categoryService->getSoftDeletedCategories($request);
                break;
            case 'spesific':
                return $this->categoryService->getCategory($request->id);
                break;
            case 'soft-delete':
                return $this->categoryService->softDeleteCategory($request->id);
                break;
            case 'restore':
                return $this->categoryService->restoreCategory($request->id);
                break;
            case 'force-delete':
                return $this->categoryService->forceDeleteCategory($request->id);
                break;
            case 'drop-down':
                return $this->categoryService->dropDownCategory($request);
                break;
            default:
                // return $this->categoryService->allCategories($request);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action parameter'
                ], Response::HTTP_BAD_REQUEST);
                break;
        }
    }

    public function store(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'create':
                return $this->categoryService->createCategory($request);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action parameter'
                ], Response::HTTP_BAD_REQUEST);
                break;
        }
    }

    public function update(Request $request, $id)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'update':
                return $this->categoryService->updateCategory($request, $id);
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
