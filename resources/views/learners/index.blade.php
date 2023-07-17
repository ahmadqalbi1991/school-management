@extends('layouts.main')
@section('title', 'Learners')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datedropper/datedropper.min.css') }}">
    @endpush


    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Learners')}}</h5>
                            <span>{{ __('Add learners details')}}</span>
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
                                <a href="#">{{ __('Learners')}}</a>
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
            @can('manage_learners')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row w-100">
                                <div class="col-md-6 col-sm-12">
                                    <h3>{{ __('Add Learner')}}</h3>
                                </div>
                                <div class="col-md-6 col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#demoModal">
                                        {{ __('Import Learners') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ empty($learner) ? route('learners.store') : route('learners.update', ['id' => $learner->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="name"
                                                   value="{{ !empty($learner) ? $learner->name : '' }}" name="name"
                                                   placeholder="Learner Name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admission_number">{{ __('Admission Number')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="admission_number"
                                                   value="{{ !empty($learner) ? $learner->admission_number : '' }}"
                                                   name="admission_number"
                                                   placeholder="Learner Admission Number" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('Email')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                                                   @if(!empty($learner)) disabled @endif
                                                   value="{{ !empty($learner) ? $learner->email : '' }}" name="email"
                                                   placeholder="Learner Email" required>
                                            @error('email')
                                            <p class="text-danger">This email is already taken</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admission_date">{{ __('Admission Date')}}<span class="text-red">*</span></label>
                                            <input type="date"
                                                   value="{{ !empty($learner) ? $learner->admission_date : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                   required class="form-control" id="admission_date"
                                                   name="admission_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="upi_number">{{ __('UPI Number')}}</label>
                                            <input type="text" class="form-control" id="upi_number"
                                                   value="{{ !empty($learner) ? $learner->upi_number : '' }}"
                                                   name="upi_number"
                                                   placeholder="UPI Number">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="parent_name">{{ __('Parent Name')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="parent_name"
                                                   value="{{ !empty($learner) ? $learner->parent_name : '' }}"
                                                   name="parent_name" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="parent_phone_number">{{ __('Parent Phone Number')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="parent_phone_number"
                                                   value="{{ !empty($learner) ? $learner->parent_phone_number : '' }}"
                                                   name="parent_phone_number" placeholder="Phone Number" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="parent_email">{{ __('Parent Email')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="parent_email"
                                                   value="{{ !empty($learner) ? $learner->parent_email : '' }}"
                                                   name="parent_email"
                                                   placeholder="Email" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="status">{{ __('Status')}}</label>
                                            <select name="status" id="status" class="form-control select2" required>
                                                <option value="">{{ __('Select Status') }}</option>
                                                <option value="active"
                                                        @if(!empty($learner) && $learner->status === 'active') selected @endif>{{ __('Active') }}</option>
                                                <option value="disable"
                                                        @if(!empty($learner) && $learner->status === 'disable') selected @endif>{{ __('Disable') }}</option>
                                                <option value="blocked"
                                                        @if(!empty($learner) && $learner->status === 'blocked') selected @endif>{{ __('Blocked') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="stream_id">{{ __('Stream')}}</label>
                                            <select name="stream_id" id="stream_id" class="form-control select2" required>
                                                <option value="">{{ __('Select Stream') }}</option>
                                                @foreach($streams as $stream)
                                                    <option @if(!empty($learner) && $stream->id === $learner->stream_id) selected @endif value="{{ $stream->id }}">{{ $stream->school_class->class }} - {{ $stream->title }} ({{ $stream->school->school_name }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="school_id">{{ __('School')}}</label>
                                            <select name="school_id" id="school_id" class="form-control select2">
                                                <option value="">{{ __('Select School') }}</option>
                                                @foreach($schools as $school)
                                                    <option
                                                        @if((!empty($learner) && $learner->school_id === $school->id) || (!empty(Auth::user()->school_id)) && Auth::user()->school_id === $school->id) selected
                                                        @endif value="{{ $school->id }}">{{ $school->school_name }}</option>
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
                        <table id="learners_table" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Email')}}</th>
                                <th>{{ __('School')}}</th>
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
    <div class="modal fade show" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">{{ __('Import Learners') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="{{ route('learners.import') }}" method="post" enctype="multipart/form-data" data-parsley-validate>
                    @csrf
                    <div class="modal-body">
                        <div class="row w-100">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="class_id">{{ __('Class') }}</label>
                                    <select name="class_id" id="class_id" class="select2 form-control" required>
                                        <option value="">{{ __('Select Class') }}</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="stream_id">{{ __('Stream') }}</label>
                                    <select name="stream_id" id="stream-id" class="select2 form-control" disabled required>
                                        <option value="">{{ __('Select Stream') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="file">{{ __('Excel File') }}</label>
                                    <input type="file" name="file" id="file" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/Cell-edit/dataTables.cellEdit.js') }}"></script>
        <script src="{{ asset('plugins/datedropper/datedropper.min.js') }}"></script>
        <!--server side permission table script-->
        <script src="{{ asset('js/learners.js') }}"></script>
    @endpush
@endsection
