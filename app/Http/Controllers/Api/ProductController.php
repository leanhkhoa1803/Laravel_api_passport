<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProductController extends Controller
{
    public function index(){
        return response()->json([
           'data'=>Product::all(),
        ]);
    }

    public function create(Request $request){
        $validate = Validator::make($request->all(),[
            'name'=>'required|string|unique:products',
            'amount'=>'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>'fails',
                'message'=>$validate->errors()->first(),
                'errors'=>$validate->errors()->toArray(),

            ]);
        }
        $user_id = Auth::id();
        $product = new Product;
        $product->user_id = $user_id;
        $product->name = $request->name;
        $product->amount = $request->amount;
        $product->save();
        return response()->json([
            'status'=>'success',
            'data'=>$product,
        ]);

    }

    public function update(Request $request){
        $validate = Validator::make($request->all(),[
            'id'=>'required',
            'name'=>'required|string',
//            'amount'=>'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>'fails',
                'message'=>$validate->errors()->first(),
                'errors'=>$validate->errors()->toArray(),

            ]);
        }
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->save();
        return response()->json([
            'status'=>'success',
            'data'=>$product,
        ]);
    }

    public function delete(Request $request){
        $validate = Validator::make($request->all(),[
            'id'=>'required'
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>'fails',
                'message'=>$validate->errors()->first(),
                'errors'=>$validate->errors()->toArray(),

            ]);
        }
        $product = Product::find($request->id);
        if(!$product){
            return response()->json([
                'status'=>'fails',
                'message'=>'Id khong ton tai',
            ]);
        }
        //xoa theo id
        $product->destroy($request->id);
        return response()->json([
           'status'=>'success',
        ]);
    }
}
