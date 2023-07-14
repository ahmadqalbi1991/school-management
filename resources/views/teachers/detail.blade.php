@extends('layouts.main')
@section('title', 'Teacher Detail | ' . $teacher->name)
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
                            <h5>{{ __('Teacher Detail')}}</h5>
                            <span>{{ __($teacher->name)}}</span>
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
                                <a href="#">{{ __($teacher->name)}}</a>
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
                        <div class="card-header"><h3>{{ __('Teacher Detail')}}</h3></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p><strong>{{ __('Name:') }} </strong>{{ $teacher->name }}</p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p><strong>{{ __('Email:') }} </strong>{{ $teacher->email }}</p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p><strong>{{ __('Contact Number:') }} </strong>{{ $teacher->phone_number }}</p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p><strong>{{ __('TCS Number:') }} </strong>{{ $teacher->tcs_number   }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h5>{{ __('Assigned Classes') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ route('teachers.save-manage-assigned-teachers') }}">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="class_id">{{ __('Class')}}<span
                                                    class="text-red">*</span></label>
                                            <select name="class_id" id="class_id"
                                                    class="select2 form-control" required>
                                                <option value="">{{ __('Select Class') }}</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="stream_id">{{ __('Stream')}}<span
                                                    class="text-red">*</span></label>
                                            <select name="stream_id" id="stream_id"
                                                    class="select2 form-control" required disabled>
                                                <option value="">{{ __('Select Stream') }}</option>
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
                        <div class="p-2">
                            <table id="teacher_detail_table" class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Class')}}</th>
                                    <th>{{ __('Stream')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{ $record->class->class }}</td>
                                        <td>{{ $record->stream->title }}</td>
                                        <td>
                                            <a href="{{ route('teachers.remove-class', ['id' => $record->id]) }}"><i
                                                    class="ik ik-trash-2 f-16 text-red"></i></a>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h5>{{ __('Assigned Subjects') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ route('teachers.save-manage-assigned-teachers-subjects') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            <label for="class_id">{{ __('Class')}}<span
                                                    class="text-red">*</span></label>
                                            <select name="class_id" id="class_id_subjects"
                                                    class="select2 form-control" required>
                                                <option value="">{{ __('Select Class') }}</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="subject_id">{{ __('Subjects')}}<span
                                                    class="text-red">*</span></label>
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
                        <div class="p-2">
                            <table id="teacher_detail_table" class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Subject')}}</th>
                                    <th>{{ __('Class')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subjects as $record)
                                    <tr>
                                        <td>{{ $record->subject->title }}</td>
                                        <td>{{ !empty($record->subject->school_class) ? $record->subject->school_class->class : '' }}</td>
                                        <td>
                                            <a href="{{ route('teachers.remove-subject', ['id' => $record->id]) }}"><i
                                                    class="ik ik-trash-2 f-16 text-red"></i></a>
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
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/Cell-edit/dataTables.cellEdit.js') }}"></script>
        <!--server side permission table script-->
        <script src="{{ asset('js/teachers.js') }}"></script>
    @endpush
@endsection
