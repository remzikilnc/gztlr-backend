<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $categoryRepository;
    protected Category $category;
    public function __construct(CategoryRepository $categoryRepository, Category $category){
        $this->categoryRepository = $categoryRepository;
        $this->category = $category;
    }
    public function index()
    {
        return app(CategoryRepository::class)->getAllCategoriesPaginated();
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(Category $category, array $params): CategoryResource
    {
        $category->fill($params);

        $category->save();

        return new CategoryResource($category->fresh());
    }
}
