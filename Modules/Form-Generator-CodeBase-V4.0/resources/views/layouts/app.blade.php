<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="//fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @include('layouts.partials.css')
    @yield('css')
</head>
<body class="layout-top-nav">
    <div id="app" class="wrapper">
        
        @if(!isset($nav) || $nav === true)
            @include('layouts.partials.top-nav')
        @endif
        
        <div class="content-wrapper">
            @if(!isset($iframe_enabled) || !$iframe_enabled)
                <div class="content-header">
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"> @yield('heading')</h1>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
            @endif

            <section class="content">
            
                <div class="container">
                    <div col-md-12>
                        <!-- @include('layouts.partials.status') -->
                    </div>
                </div>
                
                
                @yield('content')
            </section>
            
            <div class="modal fade" id="modal_div" tabindex="-1" role="dialog" 
            aria-labelledby="gridSystemModalLabel">
            </div>
        </div>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>

        @if(!isset($nav) || $nav === true)
            @include('layouts.partials.footer')
        @endif
    </div>

    @include('layouts.partials.javascript')
    @yield('footer')
    
</body>
</html>
