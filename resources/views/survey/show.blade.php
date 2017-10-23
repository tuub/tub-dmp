@extends('layouts.bootstrap')

@section('header_assets')
    @parent
    {!! HTML::script('js/survey.js') !!}
@append

@section('navigation')
@append

@section('headline')
    <div class="row col-md-24">
        {!! Html::linkRoute('dashboard', 'Back to Dashboard') !!}
    </div>
    <br/>
    <h3>Data Management Plan for TUB Project {{ $survey->plan->project->identifier }}</h3>
    <h4>Version: {{ $survey->plan->version }}</h4>
@stop

@section('body')

    <style>
        * {
            outline: 0px #336699 solid;
        }
    </style>

    <ol id="plan-toc">
        @foreach( $survey->template->sections()->active()->ordered()->get() as $section )
            <li><a href="#{{ $section->keynumber }}">{{ $section->name }}</a></li>
        @endforeach
    </ol>

    @foreach( $survey->template->sections()->active()->ordered()->get() as $section )
        <div class="col-md-24">
            <h3>
                <a class="anchor" name="{{ $section->keynumber }}"></a>
                {{ $section->full_name }}
            </h3>
            <div class="section-text">
                @foreach( $section->questions()->active()->ordered()->get() as $question )
                    @include('partials.question.show', $question)
                @endforeach
            </div>
        </div>
    @endforeach
@stop