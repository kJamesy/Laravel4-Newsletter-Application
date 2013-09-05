<form class="form-horizontal setup" action="{{URL::to('setup')}}">
    <div class="panel panel-info panel-setup col-lg-offset-2 col-lg-10">
        <div class="panel-heading">
          <h3 class="panel-title">Welcome! Setup Admin Account and Sitename</h3>
        </div> 
        <div class="panel-message"><span class="glyphicon glyphicon-bell"></span> Please note that all fields are required; password must contain an uppercase letter and a special character and be at least 7 characters long.</div><br/>
    </div>    
    <div class="form-group">
        <label for="userFirstName" class="col-lg-2 control-label">First Name</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="userFirstName" placeholder="Joe">
        </div>
    </div>
    <div class="form-group">
        <label for="userLastName" class="col-lg-2 control-label">Last Name</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="userLastName" placeholder="Bloggs">
        </div>
    </div>
    <div class="form-group">
        <label for="userEmail" class="col-lg-2 control-label">Email</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="userEmail" placeholder="mail@example.com">
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
            <input type="password" class="form-control input-lg" id="userPasswordConfirmation" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <label for="sitename" class="col-lg-2 control-label">Sitename</label>
        <div class="col-lg-10">
          <input type="text" class="form-control input-lg" id="sitename" placeholder="Jl4">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-info btn-lg">Submit</button>
        </div>
    </div>
</form>