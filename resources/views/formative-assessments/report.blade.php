@extends('layouts.main')
@section('title', 'Formative assessments Report')
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
                            <h5>{{ __('Formative Assessments Report')}}</h5>
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
                                <a href="#">{{ __('Formative Assessments Report')}}</a>
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
                            <h5 class="underline">Report Card</h5>
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
                        <div class="col-12 text-center">
                            <p>Term: {{ $term->term }} - {{ $term->year }}
                                ({{ \Carbon\Carbon::parse($term->start_date)->format('d M, Y') }}
                                - {{ \Carbon\Carbon::parse($term->end_date)->format('d M, Y') }})</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table" id="result-table">
                                <thead>
                                <tr>
                                    <th>{{ __('Subject')}}</th>
                                    <th>{{ __('Total Activities')}}</th>
                                    <th>{{ __('Attempted Activities')}}</th>
                                    <th>{{ __('Earned Points')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($result as $data)
                                    <tr>
                                        <td>{{ $data['name'] }}</td>
                                        <td>{{ $data['total_learning_activity'] }}</td>
                                        <td>{{ $data['attempted_activities'] }}</td>
                                        <td>{{ $data['attempted_points'] }}</td>
                                        <td>
                                            <a href="{{ route('reports.view-result', ['subject_id' => $data['id'], 'learner_id' => $learner->id, 'term_id' => $term->id, 'stream_id' => $stream->id]) }}"
                                               class="text-primary"><i class="ik ik-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
