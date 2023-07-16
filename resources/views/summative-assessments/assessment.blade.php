@extends('layouts.main')
@section('title', 'Formative assessments')
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
                            <h5>{{ __('Formative Assessments')}}</h5>
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
                                <a href="#">{{ __('Formative Assessments')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- end message area-->
        <!-- only those have manage_permission permission will get access -->
        <form action="{{ route('summative-assessments.save') }}" method="post" data-parsley-validate>
            @csrf
            <input type="hidden" name="subject_id" id="subject_id" value="{{ $subject->id }}">
            <div class="row clearfix">
                <!-- start message area-->
                @include('include.message')
                <div class="card">
                    <div class="card-body assessment-details">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="strand">{{ __('Class') }}</label>
                                    <select name="class_id" disabled class="form-control select2">
                                        @if(!empty($class))
                                            <option value="{{ $class->id }}" selected>{{ $class->class }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="class_id" name="class_id" value="{{ $class->id }}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="sub-strand">{{ __('Stream') }}</label>
                                    <select name="" disabled id="" class="form-control select2">
                                        @if(!empty($stream))
                                            <option value="{{ $stream->id }}" selected>{{ $stream->title }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="stream_id" name="stream_id" value="{{ $stream->id }}">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Terms') }}</label>
                                    <select name="term_id" required id="term_id" class="form-control select2">
                                        <option value="" selected>{{ __('Select Term') }}</option>
                                        @foreach($terms as $term)
                                            <option value="{{ $term->id }}">
                                                {{ $term->term }} - {{ $term->year }}
                                                ({{ \Carbon\Carbon::parse($term->start_date)->format('d M, Y') }}
                                                - {{ \Carbon\Carbon::parse($term->end_date)->format('d M, Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Exams') }}</label>
                                    <select name="exam_id" required id="exam_id" class="form-control select2" disabled>
                                        <option value="" selected>{{ __('Select Exam') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="learners-div">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="scr-vtr-dynamic"
                               class="table table-striped table-bordered nowrap">
                            <thead>
                            <tr>
                                <th>
                                    <strong>{{ __('Learners')}}</strong>
                                </th>
                                <th>{{ __('Scores') }}</th>
                                <th>{{ __('Remarks') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($learners as $key => $learner)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $learner->name }}
                                        </strong>
                                        <input type="hidden" name="learners[{{ $key }}]" value="{{ $learner->id }}">
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" required class="radmin-input" name="points[{{ $key }}]" id="score_{{ $learner->id }}" min="{{ $min }}"
                                                   data-parsley-min="{{ $min }}" data-parsley-max="{{ $max }}" max="{{ $max }}">
                                            <strong>%</strong>
                                        </div>
                                    </td>
                                    <td id="level_title_{{ $learner->id }}"></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button id="save-btn" type="submit" class="btn btn-rounded btn-success">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
    @push('script')
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('js/summative-assessment.js') }}"></script>
    @endpush
@endsection
