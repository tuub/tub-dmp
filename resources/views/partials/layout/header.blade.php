@section('header')
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>TUB-DMP</title>
            <meta charset="utf-8">

            <!-- Startup configuration -->
            <link rel="manifest" href="/manifest.webmanifest">
            <!-- Fallback application metadata for legacy browsers -->
            <meta name="application-name" content="TUB-DMP">

            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=Edge">
            <meta http-equiv="Cache-control" content="no-store">
            <meta name="Generator" content="Laravel 5.5" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

            @section('header_assets')
                {!! HTML::style('css/app.css') !!}
                {!! HTML::style('css/vendor.css') !!}
                {!! HTML::style('css/my.style.css') !!}
                {!! HTML::script('https://code.jquery.com/jquery-2.2.4.min.js') !!}
            @show

            <script>
                $(document).ready(function(){

                    toastr.options = {
                        'positionClass': 'toast-top-right',
                        'preventDuplicates': true,
                        'timeOut': 2000,
                        'fadeOut': 1500,
                        'fadeIn': 1500
                    };

                    @if(Session::has('message'))
                        var type = "{{ Session::get('alert-type', 'info') }}";
                        switch (type) {
                            case 'info':
                                toastr.info("{{ Session::get('message') }}");
                                break;

                            case 'warning':
                                toastr.warning("{{ Session::get('message') }}");
                                break;

                            case 'success':
                                toastr.success("{{ Session::get('message') }}");
                                break;

                            case 'error':
                                toastr.error("{{ Session::get('message') }}");
                                break;
                            default:
                                toastr.info("{{ Session::get('message') }}");
                                break;
                        }
                    @endif
                });
            </script>

            <style>

                * {
                    outline: 0px #336699 solid;
                }

                h1.page-header {
                    padding: 5px;
                    background-color: #fff;
                    display: inline-block;
                    border-radius: 2px;
                }

                h1.page-header * { display: block }

                .toast {
                    opacity: 1 !important;
                    margin-top: 55px !important;
                }
            </style>
        </head>

@show