@extends('dashboard._template')
@section('title')
	{{$sitename}} | Lists
@stop

@section('extracss')
    {{HTML::style('assets/jquery.tablesorter/themes/blue/style.css')}}
@stop

@section('extrajs')
    {{ HTML::script('assets/ckfinder_php_2.3.1/ckfinder/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor_4.2_full/ckeditor/ckeditor.js') }}
    {{HTML::script('assets/js/backend-lists.js')}}
@stop

@section('page')
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
            <li><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
            <li class="active"><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
            <li><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>
            <li><a href="{{URL::to('dashboard/help')}}">Help</a></li>
            <li><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
            <li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
        </ul>
        <h1 class="no-margins">{{$sitename}}</h1>
    </div>
    <div class="jumbotron">
        <center><h1><span class="glyphicon glyphicon-list"></span> Subscriber Lists</h1></center>
    </div> 
    <div class="row newsletter">
      	<div class="col-lg-12">
            @if ($lists->count() > 0)
                <div class="btn-group subs-options pull-right">
                    <button type="button" class="btn btn-info btn-lg dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog" style="vertical-align:middle"></span> Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="#new-list"><span class="glyphicon glyphicon-plus-sign"></span> Add New List</a></li>
                        <li><a data-toggle="modal" href="#update-list"><span class="glyphicon glyphicon-edit"></span> Update List</a></li>
                        <li><a data-toggle="modal" href="#delete-list"><span class="glyphicon glyphicon-trash"></span> Delete List</a></li>
                        <li class="divider"></li>
                        <li><a data-toggle="modal" href="#add-to-list"><span class="glyphicon glyphicon-plus-sign"></span> Add Subscribers</a></li>
                        <li><a data-toggle="modal" href="#remove-from-list"><span class="glyphicon glyphicon-minus-sign"></span> Remove Subscribers</a></li>
                        <li class="divider"></li>
                        <li><a data-toggle="modal" href="#email-list"><span class="glyphicon glyphicon-envelope"></span> Message Entire List</a></li>
                    </ul>
                </div>
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>No. of Subscribers</th>
                        <th>Active</th>
                        <th>Modified</th>
                        <th>&nbsp;</th>
                    </thead>              
                    <tbody>
                        @foreach ($lists as $num => $list)
                            <tr>   
                                <td>{{$num+1}}</td> 
                                <td>{{$list->name}}</td>
                                <td>{{$list->subscribers->count()}}</td>
                                <td>@if($list->active == 1)
                                        Y <span class="glyphicon glyphicon-ok" style="color: #468847"></span>
                                    @else
                                        N <span class="glyphicon glyphicon-remove" style="color: #B94A48"></span>
                                    @endif
                                </td>
                                <?php 
                                    $mysqldate = new DateTime($list->updated_at);
                                    $nicedate = $mysqldate->format("D jS M Y, H:i");
                                ?>
                                <td>{{$nicedate}}</td>
                                <td>
                                    @if($list->subscribers->count() > 0)
                                        <a class="btn btn-default" data-toggle="modal" href="#view-list-members-{{$list->id}}">View Members</a>
                                    @else
                                        <button class="btn btn-default disabled">List is empty</button>
                                    @endif
                                </td>
                            </tr> 
                        @endforeach
                    </tbody>
                </table> 

                @foreach ($lists as $list)
                    @if($list->subscribers->count() > 0)
                        <div class="modal fade" id="view-list-members-{{$list->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Subscribers in the <i>{{$list->name}}</i> List</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-hover tablesorter">
                                            <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                            </thead>   
                                            <tbody>
                                                @foreach ($list->subscribers as $key => $subscriber)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{$subscriber->first_name . ' ' . $subscriber->last_name}}</td>
                                                        <td>{{$subscriber->email}}</td>
                                                    </tr>   
                                                @endforeach
                                            </tbody>
                                        </table>                                                              
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <a id="export-subss" class="btn btn-default pull-right" href="{{URL::to('dashboard/lists/export-list/'.$list->id)}}">
                                            <span class="glyphicon glyphicon-download"></span> Download List as CSV
                                        </a>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal --> 
                    @endif
                @endforeach

            @else
                You have no lists.
                <div class="btn-group subs-options">
                    <button type="button" class="btn btn-info btn-lg dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog"></span> Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" href="#new-list"><span class="glyphicon glyphicon-plus-sign"></span> Add New List</a></li>
                    </ul>
                </div>
            @endif
     	</div>
    </div>    

    <div class="modal fade" id="new-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add a New Email List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal new-list-form" action="{{URL::to('dashboard/lists/addnew')}}">
                        <div class="panel panel-info panel-new-list col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Please Note:</h3>
                            </div> 
                            <div class="panel-message"><span class="glyphicon glyphicon-bell"></span> All fields are required.</div>
                        </div>    
                        <div class="form-group">
                            <label for="list-name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="list-name" placeholder="Name">
                            </div>
                        </div>
                        <div class="radio">
                            <label class="col-lg-offset-2 col-lg-10">
                                <input type="radio" name="list-active" id="yes" value="1">
                                Active
                            </label>
                        </div>                            
                        <div class="radio">
                            <label class="col-lg-offset-2 col-lg-10">
                                <input type="radio" name="list-active" id="no" value="0">
                                Inactive
                            </label>
                        </div> 
                    </form>                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-list">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->       

    <div class="modal fade" id="update-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal update-list-form" action="{{URL::to('dashboard/lists')}}">
                        <div class="panel panel-info panel-update-list col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select List to Update</h3>
                            </div> 
                            <div class="panel-message"></div><br />
                            <select class="form-control update-list" id="select-list-update" rel="{{URL::to('dashboard/lists/fetch')}}">
                                <option>Select List</option>
                                @foreach($lists as $list)
                                    <option  value="{{$list->id}}">
                                        {{$list->name}} 
                                    </option>
                                @endforeach
                            </select>                                
                        </div>    
                        <div class="form-group">
                            <label for="update-list-name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control input-lg" id="update-list-name" placeholder="Name" disabled>
                            </div>
                        </div>
                        <div class="radio">
                          <label class="col-lg-offset-2 col-lg-10">
                            <input type="radio" name="list-update-active" id="update-yes" value="1" disabled>
                            Activate List
                          </label>
                        </div>                            
                        <div class="radio">
                          <label class="col-lg-offset-2 col-lg-10">
                            <input type="radio" name="list-update-active" id="update-no" value="0" disabled>
                            Deactivate List
                          </label>
                        </div>                            
                    </form>                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-update-list" rel="" disabled autocomplete="off">Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->   

    <div class="modal fade" id="delete-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal delete-list-form" action="{{URL::to('dashboard/lists/delete')}}">
                        <div class="panel panel-info panel-delete-list col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select List to Delete</h3>
                            </div> 
                            <div class="panel-message"><span class="glyphicon glyphicon-bell"></span> This <b>won't</b> delete subscribers in that list.</div><br />
                            <select class="form-control delete-list" id="select-list-delete">
                                <option>Select List</option>
                                <?php $first = true; ?>
                                @foreach($lists as $list)
                                    @if($first)
                                        <?php $first = false; ?>
                                    @else
                                        <option  value="{{$list->id}}">
                                            {{$list->name}} 
                                        </option>
                                    @endif
                                @endforeach
                            </select>                                
                        </div>                                
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="delete-list-btn" rel="" disabled>
                        <span class="glyphicon glyphicon-trash"></span> Delete
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->           

    <div class="modal fade" id="add-to-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add Subscribers to List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal add-to-list-form" action="{{URL::to('dashboard/lists/add-to-list')}}">
                        <div class="panel panel-info panel-add-to-list col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select a list and then select the subscribers to  add</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span> You can select multiple subscribers (Hold Ctrl key and click on subscriber)
                            </div><br />
                        </div> 
                        <div class="form-group">
                            <div class="col-lg-12">
                                <select class="form-control select-list-add" id="select-list-add" rel="{{URL::to('dashboard/lists/fetch-subscribers')}}">
                                    <option>Select List</option>
                                    @foreach($lists as $list)
                                        <option  value="{{$list->id}}">
                                            {{$list->name}} 
                                        </option>
                                    @endforeach
                                </select> <br /><br />                                   
                                <select class="form-control" id="select-add-to-list" name="select-add-to-list[]" multiple disabled>
                                    <option value="1" id="temporay-holder">Subscribers will be populated here</option>
                                </select> 
                                <div style="margin-top: 15px; font-weight: bold">Number of selected subscribers: <span class="num-selected">0</span></div>
                            </div>
                        </div>                                                             
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="add-subs-to-list-btn" rel='' disabled>
                        Add Subscribers
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->    

    <div class="modal fade" id="remove-from-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Remove Subscribers from List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal remove-from-list-form" action="{{URL::to('dashboard/lists/remove-from-list')}}">
                        <div class="panel panel-info panel-remove-from-list col-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select a list and then select the subscribers to drop</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span> You can select multiple subscribers (Hold Ctrl key and click on subscriber)
                            </div><br />
                        </div> 
                        <div class="form-group">
                            <div class="col-lg-12">
                                <select class="form-control select-list-remove" id="select-list-remove" rel="{{URL::to('dashboard/lists/fetch-list-subs-remove')}}">
                                    <option>Select List</option>
                                    @foreach($lists as $list)
                                        @if($list->id != 1)
                                            <option  value="{{$list->id}}">
                                                {{$list->name}} 
                                            </option>
                                        @endif
                                    @endforeach
                                </select>  <br /><br />                                    
                                <select class="form-control remove-from-list" id="select-remove-from-list" name="select-remove-from-list[]" disabled multiple>
                                    <option value="1" id="temporay-holder2">Subscribers will be populated here</option>
                                </select> 
                                <div style="margin-top: 15px; font-weight: bold">Number of selected subscribers: <span class="remove num-selected">0</span></div>
                            </div>
                        </div>                                                             
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="remove-subs-from-list-btn" rel="" disabled>
                        Remove
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->           

    <div class="modal fade" id="email-list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Send Email to a List</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal email-list-form" action="{{URL::to('dashboard/lists/fetch-subs/1')}}">
                        <div class="panel panel-info panel-email-listcol-lg-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Select List to Email</h3>
                            </div> 
                            <div class="panel-message">
                                <span class="glyphicon glyphicon-bell"></span> You can select multiple lists (Hold Ctrl key and click on subscriber). <br />Only active lists with at least one subscriber can be emailed.
                            </div><br />
                        </div> 
                        <div class="form-group">
                            <div class="col-lg-12">
                                <select class="form-control email-lists" id="select-lists-email" name="select-lists-email[]" multiple>
                                    @foreach($lists as $list)
                                        @if($list->active == 1 && count($list->subscribers) > 0) 
                                            <option  value="{{$list->id}}">
                                                {{$list->name}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select> 
                                <div style="margin-top: 15px; font-weight: bold">Number of selected lists: <span class="num-selected">0</span></div>
                            </div>
                        </div>                                                             
                    </form> 
                    &nbsp;                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="email-lists-btn" rel="{{URL::to('dashboard/lists/compose-form')}}" disabled>
                        Next &raquo;
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->       
@stop