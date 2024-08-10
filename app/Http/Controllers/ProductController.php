<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['create','geOne','getAll','update']]);
    }


    public function create(Request $request){
        $validator = Validator::make(request()->all(),[
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_category_id' => 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json($validator->messages());
        }

        $categoryProductId = CategoryProduct::find($request->product_category_id);
        if(!$categoryProductId){
            return response()->json([
                "status" => "error",
                "message" => "Category product id not found"
            ], 404);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $image_name);
            $req['image'] = $path;
        }

        $newProduct = new Product();
        $newProduct -> name = $request -> name;
        $newProduct -> price = $request -> price;
        $newProduct -> product_category_id = $request -> product_category_id;
        $newProduct -> image = $path;
        $newProduct -> save();

        return response()->json([
            "status" => "success",
            "data" => $newProduct,
        ], 201);
    }

    public function getOne($id)
    {
        $getOneProduct = Product::find($id);

        if (!$getOneProduct) {
            return response()->json([
                "status" => "error",
                "message" => "Product not found"
            ], 404);
        }
        return response()->json([
            "status" => "success",
            "data" => $getOneProduct
        ], 200);
    }

    public function getAll()
    {
        $getAllProduct = Product::with('category')->get();
        return response()->json([
            "status" => "success",
            "data" => $getAllProduct
        ], 200);
    }

    public function update(Request $request, $id){
        $updateProduct = Product::find($id);
        $validator = Validator::make(request()->all(),[
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_category_id' => 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json($validator->messages());
        }

        if ($request->hasFile('image')) {
            Storage::delete($updateProduct->image);
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $image_name);
            $req['image'] = $path;
        }

        $updateProduct -> name = $request -> name;
        $updateProduct -> price = $request -> price;
        $updateProduct -> product_category_id = $request -> product_category_id;
        $updateProduct -> image = $path;
        $updateProduct -> save();

        return response()->json([
            "status" => "success",
            "data" => $updateProduct,
        ], 201);
    
    }
    public function delete($id)
    {
        $Product = Product::find($id);

        if (!$Product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product not found"
            ], 404);
        }

        $Product->delete();

        return response()->json([
            "status" => "success",
            "message" => "Product deleted successfully!"
        ], 200);
    }
}