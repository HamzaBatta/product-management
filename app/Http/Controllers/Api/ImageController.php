<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $path = $image->storeAs($folderName, $imageName, 'gallery');

        $product->images()->create([
            'path' => $path
        ]);

        return response()->json([
            'message' => __('messages.image_created')
        ]);
//        return response()->file(Storage::disk('gallery')->path ($path));
    }





    public function destroy(Product $product, int $image)
    {
        $image = Image::where('id', $image)->first();
        if ($image) {
            Storage::disk('gallery')->delete($image->path);
            $image->delete();
            return response()->json([
                'message' => __('messages.image_deleted')
            ]);
        } else {
            return response()->json([
                'message' => __('messages.image_not_found')
            ]);
        }
    }
}
