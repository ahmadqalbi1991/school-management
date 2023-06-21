@extends('layouts.main')
@section('title', 'Terms')
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
                            <h5>{{ __('Terms')}}</h5>
                            <span>{{ __('Term Details')  }}</span>
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
                                <a href="#">{{ __('Terms')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('include.message')
            @can('manage_strands')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>{{ __('Add Term')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ empty($term) ? route('terms.store') : route('terms.update', ['id' => $term->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Year')}}<span class="text-red">*</span></label>
                                            <select required name="year" id="year" class="form-control select2">
                                                <option value="">{{ __('Select Year') }}</option>
                                                @for($i = \Carbon\Carbon::now()->format('Y'); $i <= \Carbon\Carbon::now()->format('Y') + 100; $i++)
                                                    <option @if(!empty($term) && $term->year == $i) selected
                                                            @endif value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Term')}}<span class="text-red">*</span></label>
                                            <input type="text" value="{{ !empty($term) ? $term->title : '' }}" required
                                                   class="form-control" name="term" id="title"
                                                   placeholder="Enter the term">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="start_date">{{ __('Start Date')}}<span class="text-red">*</span></label>
                                            <input type="date"
                                                   value="{{ !empty($term) ? $term->start_date : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                   required class="form-control" id="start_date"
                                                   name="start_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="end_date">{{ __('End Date')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="date"
                                                   value="{{ !empty($term) ? $term->end_date : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                   required class="form-control" id="end_date"
                                                   name="end_date">
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
                        <table id="terms_table" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Start Date')}}</th>
                                <th>{{ __('End Date')}}</th>
                                <th>{{ __('Year')}}</th>
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
        <script src="{{ asset('js/terms.js') }}"></script>
    @endpush
@endsection
