@extends('frontend._template')
@section('title')
	Jl4 Newsletter
@stop
@section('page')

    <div class="container-narrow">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                <li><a class="login-box" href="{{URL::to('login-form')}}">Admin</a></li>
            </ul>
            <h3 class="text-muted">Jl4 Newsletter</h3>
        </div>
        <div class="row newsletter">
            <form class="form-horizontal password-reset-form" action="{{URL::to('password-reset-form')}}">
                <div class="panel panel-info panel-reset col-lg-offset-2 col-lg-10">
                    <div class="panel-heading">
                      <h3 class="panel-title">Good to see you again!</h3>
                    </div>
                    <div class="panel-message">Please enter your new password to proceed</div><br/>
                    Or <a class="login-box" href="{{URL::to('login-form')}}">Log in</a>
                </div>    
                <div class="form-group">
                    <label for="inputResetPass" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control input-lg" id="inputResetPass" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputResetPass2" class="col-lg-2 control-label">Re-type Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control input-lg" id="inputResetPass2" placeholder="Password">
                      <input type="hidden" value="{{$code}}" id="reset_code" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-info btn-lg">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="footer">
            <p>&copy;Jl4 <?php echo date('Y') ?></p>
        </div>
    </div> <!-- /container --> 

@stop
