@extends('layouts.main')
@section('title', 'Exams')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet"
              href="{{ asset('plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/jquery-minicolors/jquery.minicolors.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datedropper/datedropper.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Exams')}}</h5>
                            <span>{{ __('Exams Details')  }}</span>
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
                                <a href="#">{{ __('Exams')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('include.message')
            @can('manage_terms')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>{{ __('Add Exam')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ empty($exam) ? route('exams.store') : route('exams.update', ['id' => $exam->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Title')}}<span class="text-red">*</span></label>
                                            <input type="text" name="title" class="form-control" required
                                                   placeholder="Enter the title of Exam"
                                                   value="{{ !empty($exam) ? $exam->title : '' }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="term_id">{{ __('Details')}}<span
                                                    class="text-red">*</span></label>
                                            <select name="term_id" id="term_id" class="select2 form-control">
                                                <option value="">{{ __('Select Term') }}</option>
                                                @foreach($terms as $term)
                                                    <option @if(!empty($exam) && $exam->term_id === $term->id) selected @endif
                                                        value="{{ $term->id }}">{{ $term->term }} {{ $term->year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-right">
                                        <div class="form-group">
                                            <button type="submit"
                                                    class="btn btn-success btn-rounded">{{ __('Save')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-body">
                        <table id="exams_tables" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Term')}}</th>
                                <th>{{ __('Actions')}}</th>
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
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/Cell-edit/dataTables.cellEdit.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script
            src="{{ asset('plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-minicolors/jquery.minicolors.min.js') }}"></script>
        <script src="{{ asset('plugins/datedropper/datedropper.min.js') }}"></script>
        <!--server side permission table script-->
        <script src="{{ asset('js/exams.js') }}"></script>
    @endpush
@endsection
