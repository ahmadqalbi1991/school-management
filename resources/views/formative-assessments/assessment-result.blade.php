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
                            <p>Learner Name: <strong>{{ $learner->name }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>Class: <strong>{{ $stream->school_class->class }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>Stream: <strong>{{ $stream->title }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p>Admission number: <strong>{{ $learner->admission_number }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($levels as $level)
                            <div class="col-md-6 col-sm-12">
                                <p>{{ $level->title }} - <strong>{{ initials($level->title) }}</strong> ({{ $level->points }} Points)</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <p>Term: {{ $term->term }} - {{ $term->year }}
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
                                    <tbody>
                                    @foreach($subject->strands as $strand)
                                        <tr>
                                            <td>
                                                <strong>{{ $strand->title }}</strong>
                                            </td>
                                            <td colspan="{{ $levels->count() + 1 }}"></td>
                                        </tr>
                                        @foreach($strand->sub_strands as $sub_strand)
                                            <tr>
                                                <td>
                                                    <p>{{ $sub_strand->title }}</p>
                                                </td>
                                                <td colspan="{{ $levels->count() + 1 }}"></td>
                                            </tr>
                                            @foreach($sub_strand->learning_activities as $activity)
                                                <tr>
                                                    <td>
                                                        <p>{{ $activity->title }}</p>
                                                    </td>
                                                    @php
                                                        $point = 0;
                                                    @endphp
                                                    @foreach($levels as $level)
                                                        @php
                                                            $assessment = checkAssessment($level->id, $strand->id, $sub_strand->id, $activity->id, $learner->id, $subject->id, $stream->id);
                                                            if ($assessment) {
                                                                $point = $assessment->level->points;
                                                            }
                                                        @endphp
                                                        <td class="text-center">
                                                            @if($assessment)
                                                               <h5>
                                                                   <strong>
                                                                       <i class="text-green ik ik-check"></i>
                                                                   </strong>
                                                               </h5>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td>
                                                        @if($assessment)
                                                            {{ $point }}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    </tbody>
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
