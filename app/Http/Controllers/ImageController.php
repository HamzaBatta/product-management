<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{

    public function index(Product $product)
    {
        $images = Image::where('product_id', $product->id)->get();
        return $images;
    }


    public function store(Request $request, Product $product)
    {

        $validatedData = $request->validate([
            'path' => 'required|mimes:jpeg,png,jpg|max:4096'
        ]);

        $image = $request->file('path');

        $folderName = Str::slug($product->name);

        $imageName = Str::slug($product->name." ".time());

        if (!Storage::disk('gallery')->exists($folderName)) {
            Storage::makeDirectory($folderName);
        }

        $path = $image->storeAs($folderName, $imageName, 'gallery');

        $product->images()->create([
            'path' => $path
        ]);

        return response()->json([
            'message' => 'Image added successfully.'
        ]);
    }


    public function destroy(Image $image)
    {
        //
    }
}
