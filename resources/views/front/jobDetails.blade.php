@extends('front.layouts.app')

@section('content')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @if (Session::has('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ Session::get('success') }}</strong>
            </div>

            <script>
                var alertList = document.querySelectorAll(".alert");
                alertList.forEach(function(alert) {
                    new bootstrap.Alert(alert);
                });
            </script>
        @endif


        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{ Session::get('error') }}</strong>
            </div>

            <script>
                var alertList = document.querySelectorAll(".alert");
                alertList.forEach(function(alert) {
                    new bootstrap.Alert(alert);
                });
            </script>
        @endif
        <div class="container job_details_area">

            <div class="row pb-5">
                <div class="col-md-8">
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $jobs->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i>{{ $jobs->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i>{{ $jobs->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now">
                                        <a class="heart_mark" href="#"> <i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Job description</h4>
                                <p>{{ $jobs->description }}</p>
                            </div>
                            <div class="single_wrap">
                                <h4>Responsibility</h4>
                                <p>{{ $jobs->responsibility }}</p>
                            </div>
                            <div class="single_wrap">
                                <h4>Qualifications</h4>
                                {{ $jobs->qualification }}
                            </div>
                            <div class="single_wrap">
                                <h4>Benefits</h4>
                                <p>{{ $jobs->benefit }}</p>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check())
                                    <a href="javascript:void(0)" onclick="saveJobs({{$jobs->id}})" class="btn btn-secondary">Save</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled">login to apply</a>
                                @endif

                                @if (Auth::check())
                                    <a href="javascript:void(0)" onclick="applyJob({{ $jobs->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled">login to apply</a>
                                @endif

                            </div>

                        </div>

                    @if (Auth::check())
                        @if (Auth::user()->id==$jobs->user_id)
                        <div
                        class="card mt-2"
                        style="
                            background-color:$ {
                                1: orangered;
                            }
                            border-color:$ {
                                2: darkblue;
                            }
                        "
                    >
                        <div class="card-body">
                            <h4 class="card-title">Applicants</h4>
                                <div
                                    class="table-responsive"
                                >
                                    <table
                                        class="table table-striped"
                                    >
                                        <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Applied Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($applications->isNotempty())
                                            @foreach ($applications as $applicants)
                                            <tr class="">
                                                <td scope="row">{{$applicants->user->name}}</td>
                                                <td>{{$applicants->user->email}}</td>
                                                <td>{{Carbon\Carbon::parse($applicants->applied_date)->format('d M,Y')}}</td>
                                            </tr>
                                            @endforeach
                                            @endif



                                        </tbody>
                                    </table>
                                </div>

                        </div>
                    </div>
                        @endif
                    @endif




                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summery</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ Carbon\Carbon::parse($jobs->created_at)->format('d M,Y') }}</span>
                                    </li>
                                    <li>Vacancy: <span>2 Position</span></li>
                                    <li>Salary: <span>{{ $jobs->salary }}</span></li>
                                    <li>Location: <span>{{ $jobs->location }}</span></li>
                                    <li>Job Nature: <span>{{ $jobs->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $jobs->company_name }}</span></li>
                                    <li>Locaion: <span>{{ $jobs->company_location }}</span></li>
                                    <li>Webite: <span>{{ $jobs->company_website }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        function applyJob(id) {
            if (confirm('are you sure want to apply for the job?')) {
                $.ajax({
                    url: "{{ route('front.applyJobs') }}",
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}"
                    }
                })
            }
        }


        function saveJobs(id){
            $.ajax({
                url:'{{route("front.saveJobs")}}',
                type:'post',
                data:{id:id},
                dataType:'json',
                success:function(response){
                        window.location.href="{{url()->current()}}";
                }
            })
        }
    </script>
@endsection
