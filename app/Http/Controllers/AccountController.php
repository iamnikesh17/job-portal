<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageServiceProvider;
use Intervention\Image\Facades\Image;
use PDO;
use Termwind\Components\Dd;

class AccountController extends Controller
{
    // 1. it opens the registraion page
    public function accountRegister(){
        return view('front.account.registration');
    }

    // 2.process register

    public function processRegister(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:5|same:confirm_password',
            'confirm_password'=>'required|min:5'
        ]);
        if($validator->passes()){
            $user=new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->save();

            // $request->session()->flash('success','user registered sucessfully');

            return response()->json([
                'status'=>true,
                'message'=>'user registerd succesfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }


    // 3. acccount login page

    public function login(){
        return view('front.account.login');
    }
    // 4.authenticate
    public function authenticate(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('account.profile');
            }
            else{
                return redirect()->route('front.accountLogin')->with('error','either email or password is incorrect');
            }
        }
        else{
            return redirect()->route('front.accountLogin')->withErrors($validator)->withInput($request->only('email'));
        }
    }


    // this will open the profile page

    public function profile(){
        $user=Auth::user();
        // dd($user);
        return view('front.account.profile',compact('user'));
    }

    // this will log out the user

    public function logout(){
        Auth::logout();
        return redirect()->route('front.accountLogin');
    }

    // this function updates the profile of the user

    public function updateProfile(Request $request){
        $userId=Auth::user()->id;
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$userId.',id',
            'designation'=>'required',
            'mobile'=>'required'
        ]);

        if($validator->passes()){
                $user=User::find($userId);
                $user->name=$request->name;
                $user->email=$request->email;
                $user->designation=$request->designation;
                $user->mobile=$request->mobile;
                $user->save();

                // $request->session()->flash('success','updated succesfully');

                return response()->json([
                    'status'=>true,
                    'message'=>'updated successfully'
                ]);

        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }


    // this will upload the profile image


    public function updateProfilePic(Request $request){
            $validator=Validator::make($request->all(),[
                'image'=>'required|image'
            ]);

            if($validator->passes()){
                $image=$request->image;
                $ext=$image->getClientOriginalExtension();
                $imageName=time().'.'.$ext;
                $image->move(public_path('/profile_pic'),$imageName);
                User::where('id',Auth::user()->id)->update(['image'=>$imageName]);


                // generating thumbnail

                // delete old image

                File::delete(public_path('/profile_pic/'.Auth::user()->image));



                return response()->json([
                    'status'=>true,
                    'errors'=>[]
                ]);
            }
            else{
                return response()->json([
                    'status'=>false,
                    'errors'=>$validator->errors()
                ]);
            }
    }



    // this will open the create jobs form


    public function createJobs(){
        $categories=Category::orderBy('name','ASC')->get();
        $jobTypes=JobType::orderby('name','ASC')->get();

        return view('front.account.jobs.create',compact('categories','jobTypes'));
    }


    // this function stores or inserts the jobs in the jobs table

    public function saveJobs(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'jobType'=>'required',
            'Location'=>'required',
            'vacancy'=>'required',
            'salary'=>'required',
            'description'=>'required',
            'experience'=>'required',
            'company_name'=>'required'
        ]);

        if($validator->passes()){
            $jobs=new Job();
            $jobs->title=$request->title;
            $jobs->category_id=$request->category;
            $jobs->job_type_id=$request->jobType;
            $jobs->user_id=Auth::user()->id;
            $jobs->vacancy=$request->vacancy;
            $jobs->salary=$request->salary;
            $jobs->location=$request->Location;
            $jobs->benefit=$request->benefits;
            $jobs->description=$request->description;
            $jobs->responsibility=$request->responsibility;
            $jobs->qualification=$request->qualifications;
            $jobs->keywords=$request->keywords;
            $jobs->experience=$request->experience;
            $jobs->company_name=$request->company_name;
            $jobs->company_location=$request->company_location;
            $jobs->company_website=$request->company_website;
            $jobs->save();

            session()->flash('sucess','Job is posted successfully');

            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);


        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);

        }

    }

    public function myJobs(){
        $jobs=Job::where('user_id',Auth::user()->id)->with('jobType')->get();
        return view('front.account.jobs.list',compact('jobs'));
    }

    // this function will open the edit job form

    public function editJobs($jobId, Request $request){
        $categories=Category::orderBy('name','ASC')->get();
        $jobTypes=JobType::orderby('name','ASC')->get();
        $job=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$jobId
        ])->first();

        return view('front.account.jobs.edit',compact('categories','jobTypes','job'));
    }

    // this function will update the jobs

    public function updateJobs($jobId, Request $request){
        $jobs=Job::find($jobId);
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'jobType'=>'required',
            'Location'=>'required',
            'vacancy'=>'required',
            'salary'=>'required',
            'description'=>'required',
            'experience'=>'required',
            'company_name'=>'required'
        ]);

        if($validator->passes()){
            $jobs->title=$request->title;
            $jobs->category_id=$request->category;
            $jobs->job_type_id=$request->jobType;
            $jobs->user_id=Auth::user()->id;
            $jobs->vacancy=$request->vacancy;
            $jobs->salary=$request->salary;
            $jobs->location=$request->Location;
            $jobs->benefit=$request->benefits;
            $jobs->description=$request->description;
            $jobs->responsibility=$request->responsibility;
            $jobs->qualification=$request->qualifications;
            $jobs->keywords=$request->keywords;
            $jobs->experience=$request->experience;
            $jobs->company_name=$request->company_name;
            $jobs->company_location=$request->company_location;
            $jobs->company_website=$request->company_website;
            $jobs->save();

            session()->flash('sucess','Job is posted successfully');

            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);


        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);

        }
    }
    // this functino will delete the jobs

    public function deleteJobs($id,Request $request){
        $jobs=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
        ])->first();

        if(empty($jobs)){
            return response()->json([
                'status'=>false,
                'messgee'=>'Job not found'
            ]);
        }

        $jobs->delete();

        session()->flash('success','job deleted succesfully');
    }


    // this will open the applied jobs page

    public function appliedJobs(){
        $jobApplications=JobApplication::where('user_id',Auth::user()->id)->with('job','job.jobType','job.category','job.application')->get();
        return view('front.account.jobs.applied-jobs',compact('jobApplications'));
    }


    public function deleteApplicationJob($id){
        $jobApplication=JobApplication::find($id);
        if($jobApplication==null){
            return response()->json([
                'status'=>false,
                'message'=>'not found'
            ]);
        }

        $jobApplication->delete();

        session()->flash('success','job application succesfully deleted');
        return response()->json([
            'status'=>'true',
            'message'=>'application sucessfully deleted'
        ]);
    }


    public function savedJobs(){
        $savedjobs=SavedJob::where('user_id',Auth::user()->id)->with('job','job.category')->get();
        return view('front.account.jobs.saved-jobs',compact('savedjobs'));
    }

    public function deleteSavedJob(Request $request){
        $jobId=$request->id;
        $savedjob=SavedJob::find($jobId);

        $savedjob->delete();


        session()->flash('success','saved job deleted succesfully');

        return response()->json([
            'status'=>true,
            'message'=>'message deleted succesfully'
        ]);

    }


    public function updatePassword(Request $request){
        $validator=Validator::make($request->all(),[
            'old_password'=>'required',
            'new_password'=>'required|min:5',
            'confirm_password'=>'required|same:new_password'
        ]);

        if($validator->passes()){

            if (Hash::check($request->old_password,Auth::user()->password)==false) {
                session()->flash('error','your old password is incorrect ');

                return response()->json([
                    'status'=>true
                ]);

            }

            $user=User::find(Auth::user()->id);
            $user->password=Hash::make($request->new_password);
            $user->save();

            session()->flash('success','password updated succesfully');

            return response()->json([
                'status'=>true,
                'message'=>'password updated succesfully'
            ]);

        }
        else{
            return response()->json([
              'status'=>false,
              'errors'=>$validator->errors()
            ]);
        }
    }
}

