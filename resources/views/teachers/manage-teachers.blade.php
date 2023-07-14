@extends('layouts.main')
@section('title', 'Manage Teachers')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Manage Teachers')}}</h5>
                            <span>{{ __('Teachers')}}</span>
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
                                <a href="#">{{ __('Teachers')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <!-- only those have manage_permission permission will get access -->
            @can('manage_teachers')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>{{ __('Add Teachers')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ route('teachers.save-manage-assigned-teachers') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="teacher_id">{{ __('Teacher')}}<span class="text-red">*</span></label>
                                            <select name="teacher_id" id="teacher_id"
                                                    class="select2 form-control" required>
                                                <option value="">{{ __('Select Teacher') }}</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="class_id">{{ __('Class')}}<span class="text-red">*</span></label>
                                            <select name="class_id" id="class_id"
                                                    class="select2 form-control" required disabled>
                                                <option value="">{{ __('Select Class') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="stream_id">{{ __('Stream')}}<span class="text-red">*</span></label>
                                            <select name="stream_id" id="stream_id"
                                                    class="select2 form-control" required disabled>
                                                <option value="">{{ __('Select Stream') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="subject_id">{{ __('Subjects')}}<span class="text-red">*</span></label>
                                            <select name="subject_ids[]" id="subject_id" multiple
                                                    class="select2 form-control" required disabled>
                                                <option value="">{{ __('Select Subject') }}</option>
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
                        <table id="teachers_table" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Email')}}</th>
                                <th>{{ __('School')}}</th>
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
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/Cell-edit/dataTables.cellEdit.js') }}"></script>
        <!--server side permission table script-->
        <script src="{{ asset('js/teachers.js') }}"></script>
    @endpush
@endsection
