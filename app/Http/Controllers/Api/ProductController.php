<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except(['show' , 'index']);
    }

    public function index()
    {
        $products =  Product::all();
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


    public function store(Request $request){
//        dd('1');
        $validatedData = $request->validate([
            'name' => 'required|unique:products|max:155',
            'expire_date' => 'required|string',
            'category' => 'required',
            'phone_number' =>'required',
            'price' => 'required|min:0',
            'quantity' => 'required|min:1',
        ]);
        $validatedData['user_id'] = auth()->user()->id;

        Product::create($validatedData);

        return response()->json([
            'message' => 'Product has been created successfully.o'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
