@extends('front.layouts.app')


@section('content')

    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Saved Jobs</li>
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
                            <h3 class="fs-4 mb-1">Saved Jobs</h3>
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
                                        @if ($savedjobs->isNotEmpty())
                                            @foreach ($savedjobs as $savedjob)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{$savedjob->job->category->name}}</div>
                                                        <div class="info1">{{$savedjob->job->jobType->name}} . {{$savedjob->location}}</div>
                                                    </td>
                                                    <td>{{Carbon\Carbon::parse($savedjob->created_at)->format('d M,Y')}}</td>
                                                    <td>100 Applications</td>
                                                    @if ($savedjob->job->status==1)
                                                    <td>
                                                        <div class="job-status text-capitalize">active</div>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <div class="job-status text-capitalize">Deactive</div>
                                                    </td>
                                                    @endif

                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{route('front.jobDetails',$savedjob->job_id)}}"><i
                                                                            class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteSavedJob({{$savedjob->id}})"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i>
                                                                        Remove</a></li>
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
<script type="text/javascript">
    function deleteSavedJob(id){
        $.ajax({
            url:'{{route("account.deleteSavedJob")}}',
            type:'post',
            data:{id:id},
            dataType:'json',
            success:function(response){
                if(response['status']==true){
                    window.location.href="{{url()->current()}}";

                }
            }
        });
    }
</script>
@endsection
