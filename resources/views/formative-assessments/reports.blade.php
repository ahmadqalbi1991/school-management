@extends('layouts.main')
@section('title', 'Formative assessments Reports')
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Formative Assessments Reports')}}</h5>
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
                                <a href="#">{{ __('Formative Assessments Reports')}}</a>
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
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="strand">{{ __('Class') }}</label>
                                <select name="class_id" id="class_id" class="form-control select2">
                                    <option value="">{{ __('Select Class') }}</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="sub-strand">{{ __('Stream') }}</label>
                                <select name="" disabled id="stream-id" class="form-control select2">
                                    <option value="">{{ __('Select Stream') }}</option>
                                </select>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="show_table" style="display: none">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-body">
                        <table id="learners_table" class="table">
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
    @push('script')
        <script src="{{ asset('js/assessments-reports.js') }}"></script>
    @endpush
@endsection
