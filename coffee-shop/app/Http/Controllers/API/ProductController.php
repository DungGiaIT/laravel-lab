<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Paginator
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string'],
            'with' => ['sometimes', 'string'],
            'sort' => ['sometimes', Rule::in(['asc', 'desc'])],
        ]);

        $query = Product::query()
            ->when($validated['with'] ?? null, function (Builder $query, string $with): Builder {
                return $query->with(array_filter(array_map('trim', explode(',', $with))));
            })
            ->when($validated['search'] ?? null, function (Builder $query, string $search): Builder {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($validated['sort'] ?? null, function (Builder $query, string $sort): Builder {
                return $query->orderBy('price', $sort);
            });

        return $query->simplePaginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string'],
            'display_image_url' => ['required', 'url'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        return response()->json(Product::create($validated), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product): Product
    {
        $validated = $request->validate([
            'with' => ['sometimes', 'string'],
        ]);

        $with = array_filter(array_map('trim', explode(',', $validated['with'] ?? '')));
        if ($with !== []) {
            $product->loadMissing($with);
        }

        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): Product
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('products')->ignore($product)],
            'description' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'required', 'string'],
            'display_image_url' => ['sometimes', 'required', 'url'],
            'category_id' => ['sometimes', 'nullable', 'exists:categories,id'],
        ]);

        $product->update($validated);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
