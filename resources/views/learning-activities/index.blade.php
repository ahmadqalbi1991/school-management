@extends('layouts.main')
@section('title', 'Learning Activities')
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
                            <h5>{{ __('Learning Activities')}}</h5>
                            <span>{{ __('Learning Activities Details')  }}</span>
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
                                <a href="#">{{ __('Learning Activities')}}</a>
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
                        <div class="card-header"><h3>{{ __('Add Learning Activities')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ empty($learning_activity) ? route('learning-activities.store') : route('learning-activities.update', ['id' => $learning_activity->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Learning Activity Title')}}<span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="class"
                                                   value="{{ !empty($learning_activity) ? $learning_activity->title : '' }}" name="title"
                                                   placeholder="Learning Activity Title" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="sub_strand_id">{{ __('Sub Strand')}}<span class="text-red">*</span></label>
                                            <select name="sub_strand_id" id="sub_strand_id" required class="form-control select2">
                                                <option value="">{{ __('Select Sub Strand') }}</option>
                                                @foreach($sub_strands as $sub_strand)
                                                    <option @if((!empty($learning_activity)) && ($sub_strand->id === $learning_activity->sub_strand_id)) selected @endif value="{{ $sub_strand->id }}">{{ $sub_strand->title }}</option>
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
                        <table id="strands_table" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Sub Strand Title')}}</th>
                                <th>{{ __('Strand Title')}}</th>
                                <th>{{ __('Subject')}}</th>
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
        <!--server side permission table script-->
        <script src="{{ asset('js/learning-activites.js') }}"></script>
    @endpush
@endsection
