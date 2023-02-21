<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product(){
        $product=Product::latest()->paginate(5);
        return view('products',compact('product'));
    }
    public function addproduct(Request $request){
        $request->validate([
            'name'=>'required|unique:products',
            'price'=>'required'

        ],[
            'name.required'=>'Name is required',
            'name.unique'=>'Product Already Exit',
            'price.required'=>'Price is required',
        ]);
        $product=new Product();
        $product->name=$request->name;
        $product->price=$request->price;
        $product->save();
        return response()->json([
            'status'=>'success',
        ]);

    }


    public function updateproduct(Request $request){
        $request->validate([
            'up_name'=>'required|unique:products,name,'.$request->up_id,
            'up_price'=>'required'

        ],[
            'up_name.required'=>'Name is required',
            'up_name.unique'=>'Product Already Exit',
            'up_price.required'=>'Price is required',
        ]);
       Product::where('id',$request->up_id)->update([
        'name'=>$request->up_name,
        'price'=>$request->up_price,

       ]);

       return response()->json([
        'status'=>'success',

    ]);
    }


    public function deleteproduct(Request $request){
        Product::find($request->product_id)->delete();
        return response()->json([
            'status'=>'success',

        ]);

    }
             //Pagination
    public function pagination(Request $request){

            $product=Product::latest()->paginate(5);
            return view('pagination_ products',compact('product'))->render();

    }

    public function searchProduct(Request $request){
        $product=Product::where('name', 'like', '%'.$request->search_string.'%')
        ->orWhere('price', 'like', '%'.$request->search_string.'%')
        ->orderBy('id','desc')
        ->paginate(5);

        if($product->count() >= 1){
            return view('pagination_ products',compact('product'))->render();

        }else{
            return response()->json([
                'status'=>'nothing found',
            ]);

        }

}
}
