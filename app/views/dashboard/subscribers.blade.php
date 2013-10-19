@extends('dashboard._template')
@section('title')
	{{$sitename}} | Subscribers
@stop

@section('extracss')
    {{HTML::style('assets/jquery.tablesorter/themes/blue/style.css')}}
@stop

@section('extrajs')
    {{ HTML::script('assets/jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }} 
    {{ HTML::script('assets/jQuery-File-Upload/js/jquery.iframe-transport.js') }} 
    {{ HTML::script('assets/jQuery-File-Upload/js/jquery.fileupload.js') }}
    {{ HTML::script('assets/ckfinder_php_2.3.1/ckfinder/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor_4.2_full/ckeditor/ckeditor.js') }}
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
    {{HTML::script('assets/js/backend-subscribers.js')}}
    <script>
        $(function() {
            $(".tablesorter")
                .tablesorter({widthFixed: true, widgets: ['zebra']})
                .tablesorterPager({container: $("#pager")});
        });
    </script>
@stop

@section('page')
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
            <li class="active"><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
            <li><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
            <li><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>
            <li><a href="{{URL::to('dashboard/help')}}">Help</a></li>
            <li><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
            <li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
        </ul>
        <h1 class="no-margins">{{$sitename}}</h1>
    </div>
    <div class="jumbotron">
        <center><h1><span class="glyphicon glyphicon-user"></span> Subscribers</h1></center>
    </div> 
    <div class="row newsletter">
      	<div class="col-lg-12">
            @if ($subscribers->count() > 0)
                <div class="btn-group subs-options pull-right">
                    <button type="button" class="btn btn-info btn-lg dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog" style="vertical-align:middle"></span> Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="#new-sub"><span class="glyphicon glyphicon-plus-sign"></span> Add New Subscriber</a></li>
                        <li><a data-toggle="modal" href="#update-sub"><span class="glyphicon glyphicon-edit"></span> Update Subscriber</a></li>
                        <li><a data-toggle="modal" href="#delete-sub"><span class="glyphicon glyphicon-trash"></span> Delete Subscriber</a></li>
                        <li class="divider"></li>
                        <li><a data-toggle="modal" href="#import-subs"><span class="glyphicon glyphicon-upload"></span> Import From CSV File</a></li>
                        <li class="divider"></li>
                        <li><a id="export-subs" href="{{URL::to('dashboard/subscribers/export/1')}}">
                            <span class="glyphicon glyphicon-download"></span> Export to CSV File</a>
                        </li>
                        <li class="divider"></li>
                        <li><a data-toggle="modal" href="#email-subs"><span class="glyphicon glyphicon-envelope"></span> Message Subscribers
                        </a></li>
                    </ul>
                </div>
                <table class="table table-hover tablesorter">
                    <thead>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Registered</th>
                    </thead>              
                    <tbody>
                        @foreach ($subscribers as $num => $subscriber)
                            <tr>   
                                <td>{{$subscriber->id}}</td> 
                                <td>{{$subscriber->first_name}}</td>
                                <td>{{$subscriber->last_name}}</td>
                                <td>{{$subscriber->email}}</td>
                                <td>
                                    @if ($subscriber->active == 1)
                                        Y <span class="glyphicon glyphicon-ok" style="color: #468847"></span>
                                    @else
                                        N <span class="glyphicon glyphicon-remove" style="color: #B94A48"></span>
                                    @endif
                                </td>
                                <?php 
                                    $mysqldate = new DateTime($subscriber->created_at);
                                    $nicedate = $mysqldate->format("D jS M Y, H:i");
                                ?>
                                <td>{{$nicedate}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="pager" class="pager">
                    <form class="form-inline">
                        <span class="glyphicon glyphicon-step-backward first pagericons"></span>
                        <span class="glyphicon glyphicon-backward prev pagericons"></span>
                        <input type="text" class="pagedisplay form-control" disabled/>
                        <span class="glyphicon glyphicon-forward next pagericons"></span>
                        <span class="glyphicon glyphicon-step-forward last pagericons"></span>
                        <select class="pagesize form-control">
                            <option selected="selected"  value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option  value="40">40</option>
                        </select>
                    </form>
                </div>                    
            @else
                You have no subscribers.
                <div class="btn-group subs-options">
                    <button type="button" class="btn btn-info btn-lg dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog"></span> Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="#new-sub">Add New Subscriber</a></li>
                        <li class="divider"></li>
                        <li><a data-toggle="modal" href="#import-subs">Import From CSV File</a></li>
                        <li class="divider"></li>
                    </ul>
                </div>
            @endif
     	</div>
    </div>

    <div class="modal fade" id="new-sub">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add a New Subscriber</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal new-sub-form" action="{{URL::to('dashboard/subscribers/addnew')}}">
                        <div class="panel panel-info panel-new-sub col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Please Note:</h3>
                            </div> 
                            <div class="panel-message"><span class="glyphicon glyphicon-bell"></span> All fields are required</div>
                        </div>    
                        <div class="form-group">
                            <label for="sub-first-name" class="col-lg-2 control-label">First Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="sub-first-name" placeholder="First Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sub-last-name" class="col-lg-2 control-label">Last Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="sub-last-name" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sub-email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="sub-email" placeholder="Email">
                            </div>
                        </div>
                    </form>                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-sub">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->       

    <div class="modal fade" id="update-sub">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update Subscriber</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal update-sub-form" action="{{URL::to('dashboard/subscribers')}}">
                        <div class="panel panel-info panel-new-sub col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select Subscriber to Update</h3>
                            </div> 
                            <div class="panel-message"></div><br />
                            <select class="form-control update-sub" id="select-sub-update" rel="{{URL::to('dashboard/subscribers/fetch')}}">
                                <option value="">No one selected</option>
                                @foreach($subscribers as $subscriber)
                                    <option  value="{{$subscriber->id}}">
                                        {{$subscriber->first_name . ' ' . $subscriber->last_name}} &lt;{{$subscriber->email}}&gt; 
                                    </option>
                                @endforeach
                            </select>                                
                        </div>    
                        <div class="form-group">
                            <label for="update-first-name" class="col-lg-2 control-label">First Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="update-first-name" placeholder="First Name" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update-last-name" class="col-lg-2 control-label">Last Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="update-last-name" placeholder="Last Name" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update-email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="update-email" placeholder="Email" disabled>
                            </div>
                        </div>
                        <div class="radio">
                          <label class="col-lg-offset-2 col-lg-10">
                            <input type="radio" name="active" id="yes" value="1" disabled>
                            Activate Subscriber
                          </label>
                        </div>                            
                        <div class="radio">
                          <label class="col-lg-offset-2 col-lg-10">
                            <input type="radio" name="active" id="no" value="0" disabled>
                            Deactivate Subscriber
                          </label>
                        </div>                            
                    </form>                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-update-sub" data-loading-text="Working..." rel="" disabled autocomplete="off">Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->   

    <div class="modal fade" id="delete-sub">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Subscribers</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal delete-subs-form" action="{{URL::to('dashboard/subscribers/delete')}}">
                        <div class="panel panel-info panel-delete-sub col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select Subscribers to Delete</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span> You can select multiple subscribers (Hold Ctrl key and click on subscriber)
                            </div><br />
                            <select class="form-control update-sub" id="select-subs-delete" name="select-subs-delete[]" multiple>
                                @foreach($subscribers as $subscriber)
                                    <option  value="{{$subscriber->id}}">
                                        {{$subscriber->first_name . ' ' . $subscriber->last_name}} &lt;{{$subscriber->email}}&gt; 
                                    </option>
                                @endforeach
                            </select> 
                            <div style="margin-top: 15px; font-weight: bold">Number of selected subscribers: <span class="num-selected-del">0</span></div>                               
                        </div>                                
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="delete-subs-btn" rel="" disabled>
                        <span class="glyphicon glyphicon-trash"></span> Delete
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->           

    <div class="modal fade" id="import-subs">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Import Subscribers</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal import-subs-form" action="{{URL::to('dashboard/subscribers/import/1')}}">
                        <div class="panel panel-info panel-import-subs col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Upload CSV File</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span>
                                Upload will begin as soon as you select the file. First row of CSV will be skipped.
                            </div><br />                      
                        </div> 
                        <div class="form-group">
                            <div class="col-lg-5">
                                <button type="button" class="btn btn-primary btn-lg" id="browse-csv-trigger">Browse and Upload</button>
                                <input type="file" name="csvfile" id="csvfile">
                            </div>
                        </div>                             
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Done</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->     

    <div class="modal fade" id="email-subs">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Email Subscribers</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal email-subs-form" action="{{URL::to('dashboard/subscribers/fetchall/1')}}">
                        <div class="panel panel-info panel-email-sub col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select Subscribers to Email</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span> You can select multiple subscribers (Hold Ctrl key and click on subscriber)
                            </div><br />
                        </div> 
                        <div class="form-group">
                            <div class="col-lg-12">
                                <select class="form-control email-subs" id="select-subs-email" name="select-subs-email[]" multiple>
                                    @foreach($subscribers as $subscriber)
                                        @if($subscriber->active == 1)
                                            <option  value="{{$subscriber->id}}">
                                                {{$subscriber->first_name . ' ' . $subscriber->last_name}} &lt;{{$subscriber->email}}&gt; 
                                            </option>
                                        @endif
                                    @endforeach
                                </select> 
                                <div style="margin-top: 15px; font-weight: bold">Number of selected subscribers: <span class="num-selected">0</span></div>
                            </div>
                        </div>                                                             
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="email-subs-btn" rel="{{URL::to('dashboard/subscribers/compose-form')}}" disabled>
                        Next &raquo;
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->        
@stop