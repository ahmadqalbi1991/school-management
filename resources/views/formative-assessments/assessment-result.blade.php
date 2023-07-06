@extends('layouts.main')
@section('title', $subject->title)
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __($subject->title . ' Report')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __($subject->title . ' Report')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- end message area-->
        <!-- only those have manage_permission permission will get access -->
        <div class="row clearfix">
            <!-- start message area-->
            @include('include.message')
            <div class="card">
                <div class="card-body assessment-details">
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <h5 class="underline">{{ $subject->title }} Report Card</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p>{{ __('Learner Name:') }} <strong>{{ $learner->name }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>{{ __('Class:') }} <strong>{{ $stream->school_class->class }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>{{ __('Stream:') }} <strong>{{ $stream->title }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>{{ __('Admission number:') }} <strong>{{ $learner->admission_number }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <p>{{ __('No Assessment') }} - <strong>{{ __('NA') }}</strong> (0 Points)</p>
                        </div>
                        @foreach($levels as $level)
                            <div class="col-md-6 col-sm-12">
                                <p>{{ $level->title }} - <strong>{{ initials($level->title) }}</strong> ({{ $level->points }} Points)</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <p>{{ __('Term:') }} {{ $term->term }} - {{ $term->year }}
                                ({{ \Carbon\Carbon::parse($term->start_date)->format('d M, Y') }}
                                - {{ \Carbon\Carbon::parse($term->end_date)->format('d M, Y') }})</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="dt-responsive">
                                <table id="scr-vtr-dynamic"
                                       class="table table-striped table-bordered nowrap">
                                    <thead>
                                    <tr>
                                        <th>
                                        </th>
                                        @foreach($levels as $level)
                                            <th>
                                                <div class="formative-assessment">
                                                    <p class="m-0"><strong>{{ initials($level->title) }}</strong></p>
                                                </div>
                                            </th>
                                        @endforeach
                                        <th>{{ __('Points') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $total_attempted = 0;
                                        $subject_total = 0;
                                        $total_activities = 0;
                                        $average_performance = 0;
                                    @endphp
                                    <tbody>
                                    @foreach($subject->strands as $strand_key => $strand)
                                        <tr>
                                            <td>
                                                <h6><strong>{{ $strand->title }}</strong></h6>
                                            </td>
                                            <td colspan="{{ $levels->count() + 1 }}"></td>
                                        </tr>
                                        @foreach($strand->sub_strands as $sub_strand_key => $sub_strand)
                                            <tr>
                                                <td>
                                                    <strong>{{ $sub_strand->title }}</strong>
                                                </td>
                                                <td colspan="{{ $levels->count() + 1 }}"></td>
                                            </tr>
                                            @php
                                                $total_activities += $sub_strand->learning_activities->count();
                                            @endphp
                                            @foreach($sub_strand->learning_activities as $activity_key => $activity)
                                                <tr>
                                                    <td>
                                                        <p>{{ $activity->title }}</p>
                                                    </td>
                                                    @php
                                                        $point = 0;
                                                    @endphp
                                                    @foreach($levels as $level_key => $level)
                                                        <td class="text-center">
                                                            @if(!empty($activities_defination[$strand_key]['sub_strands'][$sub_strand_key]['activities'][$activity_key]['levels'][$level_key]))
                                                                @php
                                                                    $point = $activities_defination[$strand_key]['sub_strands'][$sub_strand_key]['activities'][$activity_key]['levels'][$level_key]['points'];
                                                                    $total_attempted += 1;
                                                                    $subject_total = $point + $subject_total;
                                                                @endphp
                                                                <h5>
                                                                   <strong>
                                                                       <i class="text-green ik ik-check"></i>
                                                                   </strong>
                                                               </h5>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td>
                                                        {{ $point }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    @php
                                        $average_performance = $subject_total / $total_attempted;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ __('Attempted Assessment') }}</strong></td>
                                        <td colspan="{{ $levels->count() }}"></td>
                                        <td><h5>{{ $total_attempted }}</h5></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Subject Total') }}</strong></td>
                                        <td colspan="{{ $levels->count() }}"></td>
                                        <td><h5>{{ $subject_total }}</h5></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Total Activities') }}</strong></td>
                                        <td colspan="{{ $levels->count() }}"></td>
                                        <td><h5>{{ $total_activities }}</h5></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Average Performance') }}</strong></td>
                                        <td colspan="{{ $levels->count() }}"></td>
                                        <td><h5>{{ round($average_performance, 2) }}</h5></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('js/assessments-reports.js') }}"></script>
    @endpush
@endsection
