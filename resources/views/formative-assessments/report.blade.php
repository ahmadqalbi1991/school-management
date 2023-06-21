@extends('layouts.main')
@section('title', 'Formative assessments Report')
@section('content')

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
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Email')}}</th>
                                    <th>{{ __('Parent Name')}}</th>
                                    <th>{{ __('Parent Email')}}</th>
                                    <th>{{ __('Contact Number')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script src="{{ asset('js/assessments-reports.js') }}"></script>
    @endpush
@endsection
