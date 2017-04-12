    <div class="col-md-22">
        <div class="alert alert-success">
            <!-- HEADER -->
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-1">
                    @if( $plan->is_final )
                        <i class="fa fa-lock fa-1x"></i>
                    @else
                        <i class="fa fa-file-text-o fa-1x"></i>
                    @endif
                </div>
                <div class="col-md-21 col-sm-20 col-xs-21">
                    <span class="plan-title">{{ $plan->title }}</span>
                    (Version {{ $plan->version }})
                    @unless( $plan->is_final )
                        <a href="{{ route('plan.edit', $plan->id)}}" class="edit-plan" data-rel="{{ $plan->id }}" data-toggle="modal" data-target="#edit-plan-{{$plan->id}}" title="Edit DMP">
                            <i class="fa fa-pencil"></i>
                        </a>
                    @endunless
                </div>
                <div class="col-md-2 col-sm-3 col-xs-3 text-right">
                    <span class="plan-status">{{ $plan->survey->completion }}&nbsp;%</span>
                    @if( $plan->is_final )
                        <i class="fa fa-check-square-o fa-1x" aria-hidden="true"></i><span class="hidden-xs"></span>
                    @endif
                </div>
            </div>
            <br/>
            <!-- BODY -->
            <div class="row">
                <div class="col-lg-18 col-md-18 col-sm-18 col-xs-18">
                    @unless( $plan->is_final )
                        <a href="{{ URL::route('survey.edit', [$plan->id]) }}" class="btn btn-default btn-xs" title="Edit Survey">
                            <i class="fa fa-pencil"></i><span class="hidden-sm hidden-xs"> Edit</span>
                        </a>
                    @endunless
                    <a href="{{ URL::route('survey.show', [$plan->id]) }}" class="btn btn-default btn-xs" title="View">
                        <i class="fa fa-eye"></i><span class="hidden-sm hidden-xs"> View</span>
                    </a>
                    <a href="#" class="email-plan btn btn-default btn-xs" data-rel="{{ $plan->id }}" data-toggle="modal" data-target="#email-plan" title="Email Plan">
                        <i class="fa fa-envelope-o"></i><span class="hidden-sm hidden-xs"> Email</span>
                    </a>
                    <a href="#" class="export-plan btn btn-default btn-xs" data-rel="{{ $plan->id }}" data-toggle="modal" data-target="#export-plan" title="PDF">
                        <i class="fa fa-file-pdf-o"></i><span class="hidden-sm hidden-xs"> PDF</span>
                    </a>
                    @if( $plan->is_final )
                        @unless($plan->hasNextVersion($plan->version))
                            <a href="{{ URL::route('plan.toggle', [$plan->id, $plan->version]) }}" class="btn btn-default btn-xs" title="Reopen">
                                <i class="fa fa-unlock"></i><span class="hidden-xs"> Reopen</span>
                            </a>
                            <a href="#" class="create-plan-version btn btn-default btn-xs" data-rel="{{ $plan->id }}" data-toggle="modal" data-target="#create-plan-version" title="Make new Version {{ $plan->version+1 }}">
                                <i class="fa fa-fast-forward"></i><span class="hidden-sm hidden-xs"> Create version {{ $plan->version+1 }}</span>
                            </a>
                        @endunless
                    @else
                        <a href="{{ URL::route('plan.toggle', [$plan->id]) }}" class="btn btn-default btn-xs" title="Finish"><i class="fa fa-lock"></i><span class="hidden-xs"> Finish DMP</span></a>
                    @endif
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                    @if( $plan->is_final )
                        <strong>Finished:</strong> @date( $plan->updated_at ) at @time( $plan->updated_at )
                    @else
                        <strong>Updated:</strong> @date( $plan->updated_at ) at @time( $plan->updated_at )
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.plan.modal')
