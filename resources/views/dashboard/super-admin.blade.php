@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')

        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush

    <div class="container-fluid">
        @include('include.message')

        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ count($total_schools) }}</h4>
                                <p class="mb-0">{{ __('Total Schools')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-school f-30"></i>
                            </div>
                        </div>
                        {{--                        <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $users->where('role', 'teacher')->count() }}</h4>
                                <p class="mb-0">{{ __('Teachers')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-chalkboard-teacher f-30"></i>
                            </div>
                        </div>
                        {{--                        <div id="Widget-line-chart2" class="chart-line chart-shadow" ></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $users->where('role', 'learner')->count() }}</h4>
                                <p class="mb-0">{{ __('Learners')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-user-graduate f-30"></i>
                            </div>
                        </div>
                        {{--                        <div id="Widget-line-chart3" class="chart-line chart-shadow"></div>--}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $subjects->count() }}</h4>
                                <p class="mb-0">{{ __('Total Subjects')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-book f-30"></i>
                            </div>
                        </div>
                        {{--                        <div id="Widget-line-chart4" class="chart-line chart-shadow" ></div>--}}
                    </div>
                </div>
            </div>

            <!-- product and new customar start -->
            <div class="col-12">
                <div class="card new-cust-card">
                    <div class="card-header">
                        <h3>{{ __('New Schools')}}</h3>
                    </div>
                    <div class="card-block">
                        @foreach($new_schools as $school)
                            <div class="align-middle mb-25">
                                <img src="{{ asset($school->logo) }}" alt="school image"
                                     class="rounded-circle img-40 align-top mr-15">
                                <div class="d-inline-block">
                                    <a href="javascript:void(0)"><h6>{{ $school->school_name }}</h6></a>
                                    <p class="text-muted mb-0">{{ $school->address }}</p>
                                    @if($school->active)
                                        <span class="status active"></span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h3>{{ __('Teachers')}}</h3>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Email')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('School')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($teachers as $key => $teacher)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>
                                            @if($teacher->status === 'active')
                                                <div class="p-status bg-green"></div>
                                            @endif
                                            @if($teacher->status === 'disabled')
                                                <div class="p-status bg-red"></div>
                                            @endif
                                            @if($teacher->status === 'blocked')
                                                <div class="p-status bg-yellow"></div>
                                            @endif
                                        </td>
                                        <td>{{ $teacher->school->school_name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- product and new customar end -->
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
        <!-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


        <script src="{{ asset('js/widget-statistic.js') }}"></script>
        <script src="{{ asset('js/widget-data.js') }}"></script>
        <script src="{{ asset('js/dashboard-charts.js') }}"></script>

    @endpush
@endsection
