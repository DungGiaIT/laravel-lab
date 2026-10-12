<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Paginator
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string'],
        ]);

        $query = Category::query()
            ->when($validated['search'] ?? null, function (Builder $query, string $search): Builder {
                return $query->where('name', 'like', "%{$search}%");
            });

        return $query->simplePaginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'required|max:255',
        ]);

        return response()->json(Category::create($validated), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): Category
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): Category
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories')->ignore($category)],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}
