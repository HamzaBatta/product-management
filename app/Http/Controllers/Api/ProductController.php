<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except(['show', 'index']);
    }

    public function index()
    {
        $products = Product::select('*')  // Or specify the columns you want
        ->get()
            ->each(function ($product) {
                $expirationDate = $product->getExpirationDate();
                $isExpired = ($expirationDate == 0);
                $product['is_expired'] = $isExpired;
                if ($expirationDate > 0) {
                    $product['days_until_expiration'] = floor($expirationDate);
                }
                $product->price = $product->getPrice();
            });
        return response()->json($products);
    }

    public function store(CreateProductRequest $createProductRequest)
    {
        $validatedData = $createProductRequest->all();
        $validatedData['user_id'] = auth()->user()->id;

        $product = Product::create($validatedData);
        return response()->json([
            'message' => __('messages.product_created', ['product' => $product->name])
        ]);
    }

    public function setLogo(Product $product, Request $request)
    {
        $request->validate([
            'path' => 'nullable|mimes:jpeg,png,jpg|max:4096',
            'image_id' => 'nullable|exists:images,id'
        ]);

        //Choose an exists image
        if ($request->filled('image_id') && !$request->hasFile('path') ) {
            $image = $product->images()->find($request->image_id);
            if ($image) {
                $product->logo = $image->path;
                $product->save();
                return response()->json([
                    'message' => __('messages.image_created')
                ]);
            }
        }

        //Upload a new image
        if ($request->hasFile('path') && !$request->filled('image_id')) {
            $image = $request->file('path');
            $folderName = Str::slug($product->name);
            $imageName = Str::slug($product->name." ".time());
            $path = $image->storeAs($folderName, $imageName, 'gallery');

            $product->images()->create([
                'path' => $path
            ]);

            $product->logo = $path;
            $product->save();
            return response()->json([
                'message' => __('messages.image_created')
            ]);
        }

        //Fallback return
        return response()->json([
            'message' => "Please either select an existing image or upload a new one."
        ]);
    }

    public function show(Product $product)
    {
        // Get the days until expiration
        $expirationDate = $product->getExpirationDate();
        $isExpired = ($expirationDate == 0);

        // Get the price
        $product['price'] = $product->getPrice();
        $product['is_expired'] = $isExpired;


        // Return the product data as JSON with the expired status
        return response()->json([
            'product' => $product,
        ]);
    }


    public function update(UpdateProductRequest $updateProductRequest, Product $product)
    {
        $product->update($updateProductRequest->all());
        $product->save();
        return response()->json([
            'messages' => __('messages.product_updated', ['product' => $product->name])
        ]);
    }


    public function destroy(int $product)
    {
        $product = Product::where('id', $product)->first();
        if ($product) {
            $productName = $product->name;
            $product->delete();
            return response()->json([
                'message' => __('messages.product_deleted', ['product' => $productName])
            ]);
        } else {
            return response()->json([
                'message' => __('messages.product_not_found')
            ]);
        }
    }
}
