<?php

namespace App\Http\Controllers;


use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CategoryProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['create','geOne','getAll','update','delete']]);
    }


    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $validator = Validator::make(request()->all(),[
            'name' => 'required|string',
            
        ]);
        if($validator->fails()){
            return response()->json($validator->messages());
        }
        $newCategoryProduct = new CategoryProduct();
        $newCategoryProduct -> name = $request -> name;
        $newCategoryProduct -> save();

        return response()->json([
            "status" => "success",
            "data" => $newCategoryProduct,
        ], 201);
    }

    public function getOne($id)
    {
        $getOneCategoryProduct = CategoryProduct::find($id);

        if (!$getOneCategoryProduct) {
            return response()->json([
                "status" => "error",
                "message" => "Category product not found"
            ], 404);
        }
        return response()->json([
            "status" => "success",
            "data" => $getOneCategoryProduct
        ], 200);
    }

    public function getAll()
    {
        $getAllCategoryProduct = CategoryProduct::all();
        return response()->json([
            "status" => "success",
            "data" => $getAllCategoryProduct
        ], 200);
    }

    public function update(Request $request, $id){

        $updateCategoryProduct = CategoryProduct::find($id);
        $validator = Validator::make(request()->all(),[
            'name' => 'required|unique:category_products,name,'. $updateCategoryProduct->id . ",id" 
        ]);
        if($validator->fails()){
            return response()->json($validator->messages());
        }

        $updateCategoryProduct -> name = $request -> name;
        $updateCategoryProduct -> save();

        return response()->json([
            "status" => "success",
            "data" => $updateCategoryProduct,
        ], 200);
    }
    public function delete($id)
    {
        $categoryProduct = CategoryProduct::find($id);
        if (!$categoryProduct) {
            return response()->json([
                "status" => "failed",
                "message" => "Category Product not found"
            ], 404);
        }

        $categoryProduct->delete();
        return response()->json([
            "status" => "success",
            "message" => "Category Product deleted successfully!"
        ], 200);
    }
}