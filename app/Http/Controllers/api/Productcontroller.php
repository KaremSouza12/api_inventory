<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class Productcontroller extends Controller
{
    public function index (){

        try {
            $products = Product::get();

        return response()->json(['products' => $products],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }
    public function create(ProductRequest $request){
        try {
           $product = $request->validated();

           $saveProduct = Product::create([
                'name' => $product['name'],
                'quantity_in_stock' => $product['quantity_in_stock'],
                'price_per_unit' => $product['price_per_unit'],
           ]);

           return response()->json(['success' => $saveProduct],200);

        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }

    public function show ($id){

        try {
            $product = Product::where('id',$id)->get();

            return response()->json(['success' => $product],200);

        return response()->json(['products' => $product],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }
    public function update(ProductRequest $request,$id){
        try {
           $product = $request->validated();

           $saveProduct = Product::where('id',$id)->update([
                'name' => $product['name'],
                'quantity_in_stock' => $product['quantity_in_stock'],
                'price_per_unit' => $product['price_per_unit'],
           ]);

           return response()->json(['success' => $saveProduct],200);

        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }
    public function destroy ($id){

        try {

            $product = Product::findOrFail($id)->delete();

             return response()->json(['deleted' => $product],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }
}
