<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Routing\Controller;

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
                if($expirationDate > 0)
                    $product['days_until_expiration'] = floor($expirationDate);
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



    public function update(UpdateProductRequest $updateProductRequest,Product $product)
    {
        $product->update($updateProductRequest->all());
        $product->save();
        return response()->json([
           'messages' => __('messages.product_updated',['product' => $product->name])
        ]);
    }


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
