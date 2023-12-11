<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Requests\Categories;
use App\Traits\HttpResponses;


class CategoryController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'index'
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Categories\CategoryViewAllRequest $request)
    {
        $categories = Category::where('parent_id', null)
            ->orderBy('code')
            ->get();

        return $this->success(CategoryResource::collection($categories), 'Categories fetched successfully') ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Categories\CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());

        return $this->success(new CategoryResource($category), 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories\CategoryViewRequest $request, Category $category)
    {
        return $this->success(new CategoryResource($category), 'Category fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Categories\CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->success(new CategoryResource($category), 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categories\CategoryDeleteRequest $request, Category $category)
    {
        $category->delete();

        return $this->success('', 'Category deleted successfully');
    }
}
