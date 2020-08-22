<?php

namespace App\Http\Controllers\Products;

use App\Filters\ProductFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(ProductFilters $productFilters)
    {
        $products = Product::with(['variations.stock'])->filter($productFilters)->paginate(10);
        return ProductIndexResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['variations.type', 'variations.product', 'variations.stock']);
        return new ProductResource($product);
    }
}
