<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="James Ilaki">
        {{ HTML::style('assets/bootstrapv3/css/bootstrap.css') }}
        {{ HTML::style('assets/bootstrap-glyphicons/css/bootstrap-glyphicons.css') }}
        {{ HTML::style('assets/bootstrapv3/css/narrow.css') }}
        {{ HTML::style('assets/css/style.css') }}
        @yield('extracss')
        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        {{ HTML::script('assets/bootstrapv3/js/bootstrap.js') }}
        {{ HTML::script('assets/jquery.tablesorter/jquery.tablesorter.min.js')}}
        {{ HTML::script('assets/jquery.tablesorter/jquery.tablesorter.pager.js')}}
        @yield('extrajs')        
    </head>
    <body>
        <div class="container-narrow">
            @yield('page') 
            <div class="footer">
                <div class="pull-left"><a href="https://github.com/kJamesy/Laravel4-Newsletter-Application" target="_blank">Github</a></div>
            </div>
        </div> <!-- /container -->  
     </body>
</html>
