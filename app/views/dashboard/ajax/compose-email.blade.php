<form class="form-horizontal compose-email-form" action="{{URL::to('dashboard/subscribers/send-email/1')}}">    
    <div class="form-group">
        <label for="from-name" class="col-lg-2 control-label">From (Name)</label>
        <div class="col-lg-10">
            <input type="text" class="form-control input-lg" id="from-name" placeholder="From (Name)" value="{{$user->first_name . ' ' . $user->last_name}}">
        </div>
    </div>
    <div class="form-group">
        <label for="from-email" class="col-lg-2 control-label">From (Email)</label>
        <div class="col-lg-10">
            <input type="text" class="form-control input-lg" id="from-email" placeholder="From (Email)" value="{{$user->email}}">
        </div>
    </div>
    <div class="form-group">
        <label for="to-subs" class="col-lg-2 control-label">To</label>
        <div class="col-lg-10">
            <textarea class="form-control" id="to-subs" rows="3" disabled></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="subject" class="col-lg-2 control-label">Subject</label>
        <div class="col-lg-10">
            <input type="text" class="form-control input-lg" id="subject" placeholder="Subject">
        </div>
    </div>
    <div class="form-group">
        <label for="ckeditor-1" class="col-lg-2 control-label">Email Body</label>
        <div class="col-lg-10">
            <textarea class="form-control" id="ckeditor-1" rows="3"></textarea>
        </div>
    </div> 
    <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
            <button type="submit" class="btn btn-primary btn-lg" id="process-email" >SEND</button>
        </div>
    </div>    
    <div class="panel panel-info panel-email-sub col-lg-10 col-lg-offset-2">
        <div class="panel-heading">
            <h3 class="panel-title">Compose an email</h3>
        </div> 
        <div class="panel-message">
            <span class="glyphicon glyphicon-bell"></span> Please note that all fields are required.
        </div><br />                               
    </div>                                                     
</form> 
<script>
    var editor = CKEDITOR.replace('ckeditor-1', 
        {
            // width: 600,
            // height: 450
        });

    CKFinder.setupCKEditor(editor, 'assets/ckfinder_php_2.3.1/ckfinder/');
</script>