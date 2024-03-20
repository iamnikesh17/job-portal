<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(Request $request){
        $categories=Category::where('status',1)->get();
        $jobs=Job::where([
            'status'=>1,
            'isFeatured'=>0,
        ])->with('jobType')->orderBy('created_at','DESC');

        if(!empty($request->get('keywords'))){
            $jobs=$jobs->where(function($query) use($request){
                $query->orWhere('title','like','%'.$request->get('keywords').'%');
                $query->orWhere('keywords','like','%'.$request->get('keywords').'%');
            });
        }

        if(!empty($request->get('location'))){
            $jobs=$jobs->where('location','like','%'.$request->get('location').'%');
        }

        if(!empty($request->get('categories'))){
            $jobs=$jobs->where('category_id',$request->get('categories'));
        }
        $jobs=$jobs->take(8)->get();
        return view('front.home',compact('categories','jobs'));
    }
}
