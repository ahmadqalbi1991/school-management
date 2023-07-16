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
            <div class="col-12">
                <div class="row">
                    @foreach($subjects as $subject)
                        <div class="col-md-3 col-sm-12">
                            <a href="{{ route('summative-assessments.index', ['class' => $class_slug, 'stream' => $stream_slug, 'subject' => $subject->slug]) }}">
                                <div class="class-wrapper">
                                    <h3><i class="fas fa-book-open"></i></h3>
                                    <p>{{ $subject->title }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
