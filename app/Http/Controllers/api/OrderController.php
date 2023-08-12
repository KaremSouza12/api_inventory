<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function index(){
        try {
            $orders = Order::get();

            return response()->json(['orders' => $orders],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }
    }
    public function create(Request $request){
        // dd($request);

      $order = Order::create($request->all());

      $products = $request->input('products', []);
      $quantities = $request->input('quantities', []);
      $prices = $request->input('prices', []);
      $total = $request->input('total', []);
      $total_all =  $request->input('total_amount',[]);
      for ($product=0; $product < count($products); $product++) {
        if ($products[$product] != '') {
            $order->products()->attach($products[$product], ['quantity_product_order' => $quantities[$product],'price' =>$prices[$product],'total' =>$total[$product],'total_all' =>$total_all[$product],]);
        }
    }

        if ($order) {
        $qtds = [];
        $quantityInStock = Product::whereIn('id',$request->products)->get();
          foreach ($quantityInStock as $key => $value) {

           $qtdStpck = $value->quantity_in_stock - $request->quantities[$key];
           array_push($qtds,$qtdStpck);
          }

         foreach ($request->products as $key => $value) {
          $product = Product::where('id',$request->products[$key])->update(['quantity_in_stock' => $qtds[$key]]);
         }

         return response()->json(['orders' => $order],200);
        }
      }

    public function show($id){
        try {
            $orders = Order::where('id',$id)->with('products')->get();

            return response()->json(['orders' => $orders],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],400);
        }

    }
    public function update (Request $request,int $id){

      $orderDB = Order::where('id',$id)->with('products')->get();

      $order = Order::where('id',$id)->update([
        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone,
        'status' => $request->status,
      ]);
      $orderDetach = Order::find($id);
      $orderDetach->products()->detach();
      $products = $request->input('products', []);
      $quantities = $request->input('quantities', []);
      $prices = $request->input('prices', []);
      $total = $request->input('total', []);
      $total_all =  $request->input('total_amount',[]);
      for ($product=0; $product < count($products); $product++) {
        if ($products[$product] != '') {
            $orderDetach->products()->attach($products[$product],
            [
            'quantity_product_order' => $quantities[$product],
            'price' =>$prices[$product],
            'total' =>$total[$product],
            'total_all' =>$total_all[$product]
          ]);

        }
    }

      if ($order) {
        $qtds = [];
        $qtdStpck = 0;
         foreach ($orderDB[0]->products as $key => $value) {
          $ids = $value->id;
          // $ids = explode(',',$test_value);
          $p =  Product::where('id',$ids)->get();

            $qtdStpck = $p[0]->quantity_in_stock-(int)$request->quantities[$key];
            $p[0]->update(['quantity_in_stock' => $qtdStpck]);
            $qtdStpck = 0;



        }
        return response()->json(['orders' => $order],200);

       }
    }

    public function destroy($id){

    try {
        $order = Order::find($id);
        $order->delete();
        $order->products()->detach();


        return response()->json(['orders' => $order],200);
    } catch (Exception $e) {
        return response()->json(['erro' => $e],200);
    }

}

}
