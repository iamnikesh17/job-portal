<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class JobController extends Controller
{
    //
    public function jobDetail($id){
        $jobs=Job::find($id);
        if(empty($jobs)){
            return redirect()->route('front.home');
        }

        // fetching jobApplications

        $applications=JobApplication::where('job_id',$id)->with('user')->get();
        return view('front.jobDetails',compact('jobs','applications'));
    }

    public function jobs(Request $request){
        $categories=Category::where('status',1)->get();
        $jobTypes=JobType::where('status',1)->get();
        $categorySelected="";
        $jobs=Job::where('status',1)->with('jobType')->orderBy('created_at','DESC');

        if (!empty($request->get('keywords'))) {
            $jobs = $jobs->where(function($query) use($request){
                $query->orWhere('title', 'like', '%' . $request->get('keywords') . '%');
                $query->orWhere('keywords', 'like', '%' . $request->get('keywords') . '%');
            });
        }

        // filtering  location

        if(!empty($request->get('location'))){
            $jobs=$jobs->where('location','like','%'.$request->get('location').'%');
        }

        // filtering based on categories


        if(!empty($request->get('categories'))){
            $jobs=$jobs->where('category_id',$request->get("categories"));
        }

        // filtering based on job Types
            $jobsTypesSelected=[];
        if(!empty($request->get('job_types'))){
            $jobsTypesArray=explode(',',$request->get('job_types'));
            $jobs=$jobs->whereIn('job_type_id',$jobsTypesArray);

            $jobsTypesSelected=$jobsTypesArray;

        }

        // if(!empty($request->get('keywords'))){
        //     $jobs=$jobs->where('title','like','%'.$request->get('keywords').'%');
        // }
        // if(!empty($request->get('location'))){
        //     $jobs=$jobs->where('location','like','%'.$request->get('location').'%');
        // }
        // // categories filter

        // if(!empty($request->get("category"))){
        //     $jobs=$jobs->where("category_id",$request->category);
        //     $categorySelected=$request->get('category');
        // }

        // // job types filters

        // if(!empty($request->get('job_types'))){
        //     $jobs=$jobs->whereIn('job_type_id',$request->get('job_types'));
        // }

        $jobs=$jobs->get();

        return view('front.jobs',compact('categories','jobTypes','jobs','jobsTypesSelected'));
    }


    // this function will apply the  jobs

    public function applyJobs(Request $request){
        $id=$request->id;
        $job=Job::where('id',$id)->first();
        if(empty($job)){
            session()->flash('error','job not found');

            return response()->json([
                'status'=>false,
                'message'=>'job not found'
            ]);
        }

        // user cannot apply the same job for multiple times


        if($job->user_id==Auth::user()->id){
            session()->flash('error','you can not apply your own job');

            return response()->json([
                'status'=>false,
                'message'=>"you can not apply your own job"
            ]);
        }


        $jobApplicationCount=JobApplication::where([
            'job_id'=>$id,
            'user_id'=>Auth::user()->id
        ])->count();

        if($jobApplicationCount>0){
            session()->flash('error','you have already applied for the job');
             return response()->json([
                'status'=>false,
                'message'=>'you have already applied for the job'
             ]);
        }

            $jobApply= new JobApplication();
            $jobApply->job_id=$id;
            $jobApply->user_id=Auth::user()->id;
            $jobApply->employer_id=$job->user_id;
            $jobApply->applied_date=now();
            $jobApply->save();

            $employer=User::where('id',$job->user_id)->first();
            $mailData=[
                'employer'=>$employer,
                'user'=>Auth::user(),
                'jobs'=>$job
            ];

            Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
            session()->flash('success','you have succesfully applied');

            return response()->json([
                'status'=>true,
                'message'=>'you have succesfully applied'
            ]);
        }

        public function saveJobs(Request $request){
            $id=$request->id;
            // first we check whether the job is already saved or not

            $jobsCount=SavedJob::where([
                'user_id'=>Auth::user()->id,
                'job_id'=>$id
            ])->count();

            if($jobsCount>0){
                session()->flash('success','you have already saved the job');

                return response()->json([
                    'status'=>false,
                    'message'=>'you have already saved the job'
                ]);

            }
            // this will insert the data

            $saveJobs= new SavedJob();
            $saveJobs->job_id=$id;
            $saveJobs->user_id=Auth::user()->id;
            $saveJobs->save();

            session()->flash("success",'You have successfully savedthe job');

            return response()->json([
                'status'=>true,
                'message'=>'you have succesfukky saved the job'
            ]);


        }

    }


