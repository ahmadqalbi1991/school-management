@extends('layouts.main')
@section('title', 'Term Subjects')
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
                            <h5>{{ __('Term Subjects')}}</h5>
                            <span>{{ __('Term Subject Details')  }}</span>
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
                                <a href="#">{{ __('Term Subjects')}}</a>
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
                        <div class="card-header"><h3>{{ __('Add Term Subjects')}}</h3></div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" data-parsley-validate
                                  action="{{ empty($term) ? route('term-subjects.store') : route('term-subjects.update', ['id' => $term->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Term')}}<span class="text-red">*</span></label>
                                            <select required name="term_id" id="term_id" class="form-control select2">
                                                <option value="">{{ __('Select Term') }}</option>
                                                @foreach($terms as $term_obj)
                                                    <option @if(!empty($term) && $term->id === $term_obj->id) selected
                                                            @endif value="{{ $term_obj->id }}">{{ $term_obj->term }} ({{ $term_obj->year }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Subjects')}}<span
                                                    class="text-red">*</span></label>
                                            <select required name="subject_ids[]" id="subject_id" multiple
                                                    class="form-control select2">
                                                <option value="">{{ __('Select Subjects') }}</option>
                                                @foreach($subjects as $subject)
                                                    <option
                                                        @if(!empty($term) && !empty($selected_ids) && in_array($subject->id, $selected_ids->toArray())) selected @endif
                                                        value="{{ $subject->id }}">{{ $subject->title }}</option>
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
                        <table id="terms_table" class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Terms')}}</th>
                                <th>{{ __('Year')}}</th>
                                <th>{{ __('Subjects')}}</th>
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
        <!--server side permission table script-->
        <script src="{{ asset('js/term-subjects.js') }}"></script>
    @endpush
@endsection
