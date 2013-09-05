<form class="form-horizontal login" action="{{URL::to('login')}}">
    <div class="panel panel-info panel-login col-lg-offset-2 col-lg-10">
        <div class="panel-heading">
          <h3 class="panel-title">Welcome back!</h3>
        </div> 
        <div class="panel-message">Please log in below to proceed</div><br/>
        <a href="{{URL::to('password-request-form')}}" class="password-request">Forgotten Password?</a>
    </div>    
    <div class="form-group">
        <label for="inputEmail" class="col-lg-2 control-label">Email</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="inputEmail" placeholder="Email">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-lg-2 control-label">Password</label>
        <div class="col-lg-10">
            <input type="password" class="form-control input-lg" id="inputPassword" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="remember"> Remember me
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-info btn-lg">Sign in</button>
        </div>
    </div>
</form>