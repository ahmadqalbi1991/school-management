@extends('layouts.main')
@section('title', 'Summative assessments')
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Summative Assessments')}}</h5>
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
                                <a href="#">{{ __('Summative Assessments')}}</a>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body bg-facebook p-4">
                        <div class="row">
                            <div class="col-8">
                                <h5 class="text-light">Select a class</h5>
                                <p class="text-light">Learners are organised into different classes. To assess a group of learners, you first need
                                    to select the class in which they belong and we will load the assessment sheet with the
                                    learners of that class.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    @foreach($streams as $stream)
                        <div class="col-md-3 col-sm-12">
                            <a href="{{ route('summative-assessments.index', ['class' =>  $stream->school_class->slug, 'stream' => $stream->slug]) }}">
                                <div class="class-wrapper">
                                    <h3><i class="fas fa-graduation-cap"></i></h3>
                                    <p>{{ $stream->school_class->class }} - {{ $stream->title }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
