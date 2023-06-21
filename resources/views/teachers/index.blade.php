@extends('layouts.main')
@section('title', 'Teachers')
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
                            <h5>{{ __('Teachers')}}</h5>
                            <span>{{ __('Add teachers details')}}</span>
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
                                  action="{{ empty($teacher) ? route('teachers.store') : route('teachers.update', ['id' => $teacher->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="name"
                                                   value="{{ !empty($teacher) ? $teacher->name : '' }}" name="name"
                                                   placeholder="Teacher Name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone_number">{{ __('Phone Number')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="phone_number"
                                                   value="{{ !empty($teacher) ? $teacher->phone_number : '' }}"
                                                   name="phone_number" placeholder="Phone Number" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('Email')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                                                   @if(!empty($teacher)) disabled @endif
                                                   value="{{ !empty($teacher) ? $teacher->email : '' }}" name="email"
                                                   placeholder="Email" required>
                                            @error('email')
                                            <p class="text-danger">This email is already taken</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tsc_number">{{ __('TSC Number')}}</label>
                                            <input type="text" class="form-control" id="tsc_number"
                                                   value="{{ !empty($teacher) ? $teacher->tsc_number : '' }}"
                                                   name="tsc_number"
                                                   placeholder="TSC Number">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="school_id">{{ __('School')}}</label>
                                            <select name="school_id" id="school_id" class="form-control select2">
                                                <option value="">{{ __('Select School') }}</option>
                                                @foreach($schools as $school)
                                                    <option
                                                        @if(!empty($teacher) && $teacher->school_id === $school->id) selected
                                                        @endif value="{{ $school->id }}">{{ $school->school_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if(!empty($teacher))
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="status">{{ __('Status')}}</label>
                                                <select name="status" id="status" class="form-control select2">
                                                    <option value="active"
                                                            @if($teacher->status === 'active') selected @endif>{{ __('Active') }}</option>
                                                    <option value="disable"
                                                            @if($teacher->status === 'disable') selected @endif>{{ __('Disable') }}</option>
                                                    <option value="blocked"
                                                            @if($teacher->status === 'blocked') selected @endif>{{ __('Blocked') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
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
