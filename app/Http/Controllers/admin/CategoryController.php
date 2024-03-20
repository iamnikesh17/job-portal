<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function create(){
        return view('admins.category.create');
    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|unique:categories,name'
        ]);

        if($validator->passes()){
            $category=new Category();
            $category->name=$request->name;
            $category->status=$request->status;
            $category->save();

            // session()->flash('success','category added succesfully');
            return response()->json([
                'status'=>true,
                'message'=>'category added succesfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit(){

    }

    public function update(){

    }

    public function index(){
        return view('admins.category.list');
    }
}
