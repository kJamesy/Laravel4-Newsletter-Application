@extends('dashboard._template')
@section('title')
	{{$sitename}} | Settings
@stop

@section('extrajs')
    {{HTML::script('assets/js/backend-settings.js')}}
    </script>
@stop

@section('page')
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
            <li><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
            <li><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
            <li><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>
            <li><a href="{{URL::to('dashboard/help')}}">Help</a></li>
            <li class="active"><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
            <li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
        </ul>
        <h1 class="no-margins">{{$sitename}}</h1>
    </div>
    <div class="jumbotron">
        <center><h1><span class="glyphicon glyphicon-wrench"></span> Settings</h1></center>
    </div> 
    <div class="row newsletter">
      	<div class="col-lg-12">
            <form class="form-horizontal setup" action="{{URL::to('dashboard/settings')}}">
                <div class="panel panel-info panel-setup col-lg-offset-2 col-lg-10">
                    <div class="panel-heading">
                      <h3 class="panel-title">You can change site settings here</h3>
                    </div> 
                    <div class="panel-message"><span class="glyphicon glyphicon-bell"></span> Please note that all fields are required; password must contain an uppercase letter and a special character and be at least 7 characters long.</div><br/>
                </div>    
                <div class="form-group">
                    <label for="userFirstName" class="col-lg-2 control-label">First Name</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control input-lg" id="userFirstName" value="{{$user->first_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="userLastName" class="col-lg-2 control-label">Last Name</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control input-lg" id="userLastName" value="{{$user->last_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="userEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control input-lg" id="userEmail" value="{{$user->email}}">
                    </div>
                </div>
                <div class="form-group form-group-password">
                    <label for="userPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                        <input type="password" class="form-control input-lg" id="userPassword" placeholder="Password">
                    </div>
                </div>
                <div class="form-group form-group-password">
                    <label for="userPasswordConfirmation" class="col-lg-2 control-label">Password Confirmation</label>
                    <div class="col-lg-10">
                        <input type="password" class="form-control input-lg" id="userPasswordConfirmation" placeholder="Password Confirmation">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sitename" class="col-lg-2 control-label">Sitename</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control input-lg" id="sitename"  value="{{$sitename}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-info btn-lg">Submit</button>
                    </div>
                </div>
            </form>
     	</div>
    </div>
@stop