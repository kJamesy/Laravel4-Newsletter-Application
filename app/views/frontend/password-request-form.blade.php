<form class="form-horizontal password-request-form" action="{{URL::to('password-request-form')}}">
    <div class="panel panel-info panel-reminder col-lg-offset-2 col-lg-10">
        <div class="panel-heading">
          <h3 class="panel-title">Sorry about that!</h3>
        </div>
        <div class="panel-message">Please enter your email below and we'll email you some help </div><br/>
        <a class="login-box" href="{{URL::to('login-form')}}">Remembered your password?</a>
    </div>    
    <div class="form-group">
        <label for="reminderEmail" class="col-lg-2 control-label">Email</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="reminderEmail" placeholder="Email">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-info btn-lg">Submit</button>
        </div>
    </div>
</form>