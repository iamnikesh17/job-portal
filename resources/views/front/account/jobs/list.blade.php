@extends('front.layouts.app')

@section('content')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">My Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="s-body text-center mt-3">
                        <img src="{{ asset('profile_pic/' . Auth::user()->image) }}" alt="avatar"
                        class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-1 fs-6">Full Stack Developer</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Change Profile Picture</button>
                        </div>
                    </div>
                </div>
                <div class="card account-nav border-0 shadow mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush ">
                            <li class="list-group-item d-flex justify-content-between p-3">
                                <a href="account.html">Account Settings</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="post-job.html">Post a Job</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="my-jobs.html">My Jobs</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="job-applied.html">Jobs Applied</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="saved-jobs.html">Saved Jobs</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                @if (Session::has('success'))
                <div
                class="alert alert-primary alert-dismissible fade show"
                role="alert"
            >
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
                <strong>{{Session::get('sucess')}}</strong>
            </div>
                @endif



                <script>
                    var alertList = document.querySelectorAll(".alert");
                    alertList.forEach(function (alert) {
                        new bootstrap.Alert(alert);
                    });
                </script>

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">My Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{route('account.createJobs')}}" class="btn btn-primary">Post a Job</a>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Job Created</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobs->isNotEmpty())
                                        @foreach ($jobs as $job)
                                        <tr class="active">
                                            <td>
                                                <div class="job-name fw-500">{{$job->title}}</div>
                                                <div class="info1">{{$job->jobType->name}} . {{$job->location}}</div>
                                            </td>
                                            <td>{{Carbon\Carbon::parse($job->created_at)->format('D M,Y')}}</td>
                                            <td>0 Applications</td>
                                            <td>
                                                <div class="job-status text-capitalize">{{$job->status}}</div>
                                            </td>
                                            <td>
                                                <div class="action-dots float-end">
                                                    <button  class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="job-detail.html"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="{{route('account.editJobs',$job->id)}}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteJob({{$job->id}})"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif


                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('customJs')
        <script>
            function deleteJob(id){
                var  url="{{route('account.deleteJob','ID')}}";
                var newUrl=url.replace("ID",id);

                $.ajax({
                    url:newUrl,
                    type:'delete',
                    data:{},
                    dataType:'json',
                    success:function(response){
                        window.location.href="{{route('account.myJobs')}}";
                    }
                });

            }
        </script>
@endsection
