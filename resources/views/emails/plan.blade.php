@if( $recipient['name'] )
    Dear Mrs./Mr. {!! $recipient['name'] !!},
@else
    Hello,
@endif

in the attachment please find the Data Management Plan for TUB project "{!! $plan->getTitle() !!}" (Version {!! $plan->version !!}).

Best regards,
{!! Auth::user()->name !!}


---
This e-mail has been automatically generated and sent via TUB-DMP.

TUB-DMP is the web tool for creating data management plans at TU Berlin.
https://dmp.tu-berlin.de

More information: http://www.szf.tu-berlin.de/menue/dienste_tools/datenmanagementplan_tub_dmp/