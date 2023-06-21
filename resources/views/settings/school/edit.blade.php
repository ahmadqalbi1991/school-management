@extends('layouts.main')
@section('title', 'Edit School Settings')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/mohithg-switchery/dist/switchery.min.css') }}">
    @endpush


    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('School Settings')}}</h5>
                            <span>{{ __('Edit school setting details')}}</span>
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
                                <a href="#">{{ __('School Settings')}}</a>
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
            @can('manage_settings')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>{{ __('Edit School Setting')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  enctype="multipart/form-data"
                                  action="{{ route('settings.schools.update', ['id' => $school->id]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="school_name">{{ __('Name')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="school_name"
                                                   value="{{ $school->school_name }}"
                                                   name="school_name"
                                                   placeholder="School Setting Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{ __('Email')}}<span class="text-red">*</span></label>
                                            <input type="email" class="form-control" id="email"
                                                   value="{{ $school->email }}" name="email"
                                                   placeholder="Email Address" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="address">{{ __('School Address')}}<span
                                                    class="text-red">*</span></label>
                                            <textarea class="form-control" id="address" name="address" rows="5"
                                                      placeholder="School Address"
                                                      required>{{ $school->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone_number">{{ __('Phone Number')}}<span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="phone_number"
                                                   value="{{ $school->phone_number }}"
                                                   name="phone_number"
                                                   placeholder="Phone Number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="school_website">{{ __('School Website')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="school_website"
                                                   value="{{ $school->school_website }}"
                                                   name="school_website"
                                                   placeholder="School Website" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="school_moto">{{ __('School Moto')}}<span
                                                    class="text-red">*</span></label>
                                            <textarea class="form-control" id="school_moto" name="school_moto" rows="5"
                                                      placeholder="School Moto"
                                                      required>{{ $school->school_moto }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <h4 class="sub-title">{{ __('Status')}}</h4>
                                            <input value="1" name="status" type="checkbox" class="js-single"
                                                   @if($school->active) checked @endif/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admin_ids">{{ __('Admin')}}</label>
                                            <select name="admin_ids[]" id="admin_ids" multiple
                                                    class="form-control select2" required>
                                                @foreach($admins as $admin)
                                                    <option @if(in_array($admin->id, $selected_admins)) selected
                                                            @endif value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <input accept="image/*" type="file" class="form-control" name="logo">
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

    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
        <!--server side permission table script-->
        <script src="{{ asset('js/school.js') }}"></script>
    @endpush
@endsection
