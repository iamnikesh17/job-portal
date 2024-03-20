@extends('front.layouts.app')

@section('content')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option value="">Latest</option>
                            <option value="">Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" name="searchForm" id="searchForm">

                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input value="{{Request::get("keywords")}}" type="text" placeholder="Keywords" name="keyword" id="keywords"
                                    class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input value="{{Request::get("location")}}" type="text" placeholder="Location" name="location" id="location"
                                    class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    @if ($categories->isNotEmpty())
                                        <option value="">Select  a Category</option>
                                        @foreach ($categories as $category)
                                            <option {{ $category->id == Request::get('categories') ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>

                            <div class="mb-4">
                                <h2>Job Type</h2>

                                @if ($jobTypes->isNotEmpty())
                                    @foreach ($jobTypes as $jobType)
                                        <div class="form-check mb-2">
                                            <input {{(in_array($jobType->id,$jobsTypesSelected))?'checked':''}} class="form-check-input" name="job_type"
                                                id="jobTypeArray-{{ $jobType->id }}" type="checkbox"
                                                value="{{ $jobType->id }}">
                                            <label class="form-check-label "
                                                for="job-type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option value="">1 Year</option>
                                    <option value="">2 Years</option>
                                    <option value="">3 Years</option>
                                    <option value="">4 Years</option>
                                    <option value="">5 Years</option>
                                    <option value="">6 Years</option>
                                    <option value="">7 Years</option>
                                    <option value="">8 Years</option>
                                    <option value="">9 Years</option>
                                    <option value="">10 Years</option>
                                    <option value="">10+ Years</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-block btn-primary">search</button>
                            </div>
                    </form>
                </div>


            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">
                    <div class="job_lists">
                        <div class="row">

                            @if ($jobs->isNotempty())
                                @foreach ($jobs as $job)
                                    <div class="col-md-4">
                                        <div class="card border-0 p-3 shadow mb-4">
                                            <div class="card-body">
                                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                                <p>{{ Str::words($job->description, 5) }}.</p>
                                                <div class="bg-light p-3 border">
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                        <span class="ps-1">{{ $job->location }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                        <span class="ps-1">{{ $job->jobType->name }}</span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                        <span class="ps-1">{{ $job->salary }}</span>
                                                    </p>
                                                </div>

                                                <div class="d-grid mt-3">
                                                    <a href="job-detail.html" class="btn btn-primary btn-lg">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

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
$("#searchForm").submit(function(e){
    e.preventDefault();
    var url = "{{ url()->current() }}?";
    var keywords = $("#keywords").val();
    if(keywords!=""){
        url += "keywords=" + keywords;  // Concatenate the keywords to the URL
    }

    var location=$("#location").val();
    if(location!=""){
        url+="&location=" + location;
    }

    var categories=$("#category").val();
    if(categories!=""){
        url+="&categories="+categories;
    }


    var jobTypeArray=$("input:checkbox[name='job_type']:checked").map(function(){
        return $(this).val();
    }).get();


    if(jobTypeArray.length>0){
        url+='&job_types='+jobTypeArray;
    }
    window.location.href = url;
});

        // $("#keywords").change(function(){
        //     apply_filters();
        // })

        // $("#location").change(function(){
        //     apply_filters();
        // });

        // $("#category").change(function(){
        //     apply_filters();
        // });

        // $("#jobType").change(function(){

        // });

        // function apply_filters(){
        //     var keywords=$("#keywords").val();
        //     var url="{{ url()->current() }}?";

        //     // keywords filters
        //     url+='keywords='+keywords;

        //     // location filter
        //     var location=$("#location").val();
        //     if(location!=""){
        //         url+="&location="+location;
        //     }

        //     // category filter

        //     var category=$("#category").val();
        //     if(category!=""){
        //         url+="&category="+category
        //     }

        //     // jobType filters
        //     var jobtypes=[];
        //     $(".job-types").each(function(){
        //             if($(this).is(":checked")){
        //                 jobtypes.push($(this).val())
        //             }
        //     });

        //     if(jobtypes.length>0){
        //         url+="&jobTypesArray[]="+jobtypes.join(',');
        //     }
        //     window.location.href=url;
        // }
    </script>
@endsection
