@extends('front.layouts.app')

@section('content')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Jobs Applied</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">

                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <h3 class="fs-4 mb-1">Jobs Applied</h3>
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
                                    @if ($jobApplications->isNotEmpty())
                                            @foreach ($jobApplications as $jobApplication)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{$jobApplication->job->category->name}}</div>
                                                        <div class="info1">{{$jobApplication->job->location}}</div>
                                                    </td>
                                                    <td>{{Carbon\Carbon::parse($jobApplication->applied_date)->format('d M,Y')}}</td>
                                                    <td>{{$jobApplication->job->application->count()}}</td>
                                                    <td>
                                                        @if ($jobApplication->job->status==1)
                                                        <div class="job-status text-capitalize">active</div>
                                                        @else
                                                        <div class="job-status text-capitalize">Deactive</div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{-- <div class="action-dots float-end">
                                                            <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                                {{-- <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" href="{{route('front.jobDetails',$jobApplication->job->job_type_id)}}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                                    <li><a class="dropdown-item" href="#"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                                </ul> --}}

                                                        {{-- </div> --}}

                                                        <a href="javascript:void(0)" onclick="deleteJob({{$jobApplication->id}})" class="btn btn-primary">Remove</a>
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
    <script type="text/javascript">
        function deleteJob(id){
            var url='{{route("account.deleteApplicationJob","Id")}}';
            var newUrl=url.replace("Id",id);
            $.ajax({
                url:newUrl,
                type:'delete',
                data:{},
                dataType:'json',
                headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },

                success:function(response){
                    window.location.href="{{url()->current()}}"
                }

            });
        }

    </script>
@endsection
