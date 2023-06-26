<style>
    .school-detail-wrapper {
        display: block;
        width: 100%;
    }

    .school-detail-wrapper > div {
        float: left;
        padding: 0 20px;
        height: 200px;
    }

    .school-img {
        width: 20%;
        position: relative;
    }

    .school-img img {
        top: 35%;
        position: absolute;
        width: 75%;
    }

    .school-address {
        width: 72%;
    }

    .pdf-wrapper {
        font-family: sans-serif;
        padding: 0 25px;
    }

    .term-details {
        width: 100%;
        text-align: center;
        font-size: 16px;
    }

    .learners-details {
        width: 100%;
    }

    .learners-details p {
        float: left;
        padding: 0 10px;
    }

    .date-generated {
        width: 40%;
        font-weight: 600;
    }

    table, td, th {
        border: 1px solid #ddd;
        text-align: left;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 15px;
    }

    .subjects-table {
        margin-top: 3rem;
    }

    .school-address h5 {
        font-size: 20px;
        margin: 0;
        margin-bottom: 1rem;
    }

    .school-address p {
        margin: 0;
        margin-bottom: 0.6rem;
        font-size: 14px;
    }

    .general-text {
        margin-top: 25px;
        font-size: 18px;
        font-weight: 600;
    }

    .signatures {
        margin: 2rem 0;
        width: 100%;
    }

    .signatures > div {
        width: 45%;
        float: left;
    }

    .border {
        height: 50px;
        border-bottom: 1px solid;
        width: 150px;
    }
</style>
<div class="pdf-wrapper">
    <div class="school-detail-wrapper">
        <div class="school-img">
            <img src="{{ public_path($school->logo) }}" alt="">
        </div>
        <div class="school-address">
            <h5>{{ $school->school_name }}</h5>
            <p><strong>{{ __('School Address') }}: </strong>{{ $school->address }} <strong>{{ __('Telephone') }}: </strong>{{ $school->phone_number }}</p>
            <p><strong>{{ __('Email') }}: </strong>{{ $school->email }}</p>
            <p><strong>{{ __('Website') }}: </strong><a href="">{{ $school->school_website }}</a></p>
        </div>
    </div>
    <div class="term-details">
        <p>{{ $term->term }}, {{ $term->year }}</p>
        <h4>{{ __('Formative Assessment Summary Report') }}</h4>
    </div>
    <div class="learners-details">
        <p><strong>{{ __('Learner') }}: </strong>{{ $learner->name }}</p>
        <p><strong>{{ __('Admission') }} #: </strong>{{ $learner->admission_number }}</p>
        <p><strong>{{ __('Class') }}: </strong>{{ $stream->school_class->class }}</p>
        <p><strong>{{ __('Stream') }}: </strong>{{ $stream->title }}</p>
    </div>
    <div class="date-generated">Date Generated: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
    <div class="subjects-table">
        <table>
            <thead>
            <tr>
                <th>{{ __('Subjects') }}</th>
                <th>{{ __('Performance') }}</th>
                <th>{{ __('Remarks') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total_points = 0;
            @endphp
            @foreach($results as $result)
                @php
                    $total_points += $result['attempted_points'];
                @endphp
                <tr>
                    <td>{{ $result['name'] }}</td>
                    <td style="text-align: right">{{ $result['attempted_points'] }}</td>
                    <td>{{ checkPointsCriteria($result['attempted_points']) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>{{ __('Final Performance') }}</th>
                <th style="text-align: right">{{ round($total_points, 2) }}</th>
                <th>{{ checkPointsCriteria($total_points, true) }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="general-text">This term formative assessment {{ $total_points }}, {{ checkPointsCriteria($total_points, true) }}</div>
    <p>Your rating was {{ checkPointsCriteria($total_points, true) }}</p>
    <div class="signatures">
        <div class="teacher">
            <p>{{ __('Signature') }}</p>
            <div class="border"></div>
            <p>Class Teacher</p>
        </div>
        <div class="principle">
            <p>{{ __('Signature') }}</p>
            <div class="border"></div>
            <p>Principle</p>
        </div>
    </div>
</div>
