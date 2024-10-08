<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Routing\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
                $isExpired = (!$expirationDate == 0);
                $product['is_expired'] = $isExpired;
                $product->price = $product->getPrice();
            });
        return response()->json([
            $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
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

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Get the days until expiration
        $expirationDate = $product->getExpirationDate();
        $isExpired = (!$expirationDate == 0);

        // Get the price
        $product['price'] = $product->getPrice();

        // Return the product data as JSON with the expired status
        return response()->json([
            'product' => $product,
            'is_expired' => $isExpired,
            'days_until_expiration' => floor($expirationDate), // Include the number of days until expiration (if valid)
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $updateProductRequest,Product $product)
    {
        $product->update($updateProductRequest->all());
        $product->save();
        return response()->json([
           'messages' => __('messages.product_updated',['product' => $product->name])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $product)
    {
        $product = Product::where('id' , $product)->first();
        if($product){
            $productName = $product->name;
            $product->delete();
            return response()->json([
                'message' => __('messages.product_deleted' , ['product' => $productName])
            ]);
        }else{
            return  response()->json([
               'message' => __('messages.product_not_found')
            ]);
        }
    }
}
