@extends('dashboard._template')
@section('title')
	{{$sitename}} | Help
@stop

@section('extrajs')
    {{ HTML::script('assets/ckfinder_php_2.3.1/ckfinder/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor_4.2_full/ckeditor/ckeditor.js') }}
@stop

@section('page')

    <div class="container-narrow">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
                <li><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
                <li><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
                <li><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>                
                <li class="active"><a href="{{URL::to('dashboard/help')}}">Help</a></li>
                <li><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
                <li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
            </ul>
            <h3 class="text-muted">{{$sitename}}</h3>
        </div>
        <div class="jumbotron">
            <center><h1><span class="glyphicon glyphicon-bullhorn"></span> Help</h1></center>
        </div>         
        <div class="row newsletter">
          	<div class="col-lg-12">
                <form class="form-horizontal help-form" action="{{URL::to('dashboard/help')}}" method="post">  
                    <div class="alert alert-info">Stuck? Drop us an email and help will soon find you!</div>  
                    @if ($errors->first())
                        <div class="alert alert-danger alert-block">
                            @foreach ($errors->all(':message<br />') as $error)
                                {{$error}}
                            @endforeach
                        </div>
                    @endif
                    @if (Session::has('success'))
                        {{ '<div class="alert alert-success">'.Session::get('success').'</div>'}}
                    @endif   
                    @if (Session::has('error'))
                        {{ '<div class="alert alert-danger">'.Session::get('error').'</div>'}}
                    @endif                 
                    <div class="form-group">
                        <label for="subject" class="col-lg-2 control-label">Subject</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control input-lg" id="subject" name="subject" placeholder="Subject">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ckeditor-3" class="col-lg-2 control-label">Message</label>
                        <div class="col-lg-10">
                            <textarea class="form-control" id="ckeditor-3" name="message" rows="3"></textarea>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="process-email" >SEND</button>
                        </div>
                    </div>                                                     
                </form> 
                <script>
                    var editor = CKEDITOR.replace('ckeditor-3', 
                        {
                            // width: 600,
                            // height: 450
                        });

                    CKFinder.setupCKEditor(editor, 'assets/ckfinder_php_2.3.1/ckfinder/');
                </script>
         	</div>
        </div>
        <div class="footer">
            <p>
                <div class="pull-left">&copy;{{$sitename . ' ' . date('Y')}}</div>
                <div class="pull-right"><a target="_blank" href="https://twitter.com/kJamesy">kJamesy</a></div>
            </p>
        </div>        

    </div> <!-- /container --> 
@stop