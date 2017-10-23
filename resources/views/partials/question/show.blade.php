@if ($question->isChild())
    <div class="col-md-offset-1">
@endif
<div class="row">
    <div class="col-md-24">
        <span class="question-text">
            @if( $question->input_type == 'headline')
                <span class="headline_question">
            @endif

            <div style="display: inline-block; width: 15px;">
                @if(is_numeric( $question->keynumber ) || str_contains($question->keynumber, '/'))
                    {{ $question->keynumber }}.
                @else
                    {{ $question->keynumber }}
                @endif
            </div>
            @if( $question->output_text )
                {{ $question->output_text }}
            @else
                {{ $question->text }}
            @endif
            @if( $question->input_type == 'headline')
                </span>
            @endif
        </span>
    </div>
    <div class="col-md-24">
        <div style="display: inline-block; margin-top: 10px; margin-left: 18px;">
            <div class="question-answer-text">
                @if( $question->input_type != 'headline')
                    {!! App\Answer::get( $survey, $question, 'html' ) !!}
                @endif
            </div>
        </div>
    </div>
</div>
<br/>
@if ($question->isChild())
    </div>
@endif