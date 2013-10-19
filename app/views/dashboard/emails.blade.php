@extends('dashboard._template')
@section('title')
	{{$sitename}} | Emails
@stop

@section('extracss')
    {{HTML::style('assets/select2-3.4.2/select2.css')}}
@stop

@section('extrajs')
    {{ HTML::script('assets/ckfinder_php_2.3.1/ckfinder/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor_4.2_full/ckeditor/ckeditor.js') }}
    {{ HTML::script('assets/select2-3.4.2/select2.js')}}
    {{HTML::script('assets/js/backend-emails.js')}}
@stop

@section('page')
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
            <li><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
            <li><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
            <li class="active"><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>
            <li><a href="{{URL::to('dashboard/help')}}">Help</a></li>
            <li><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
            <li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
        </ul>
        <h1 class="no-margins">{{$sitename}}</h1>
    </div>
    <div class="jumbotron">
        <center><h1><span class="glyphicon glyphicon-envelope"></span> Emails</h1></center>
    </div>        
    <div class="row newsletter">
      	<div class="col-lg-12 emails-page">
            <ul class="nav nav-tabs" id="emailsTab">
                <li class="active">
                    <a href="#compose" class="compose" rel="{{URL::to('dashboard/emails/compose-email')}}">Compose</a>
                </li>
                <li>
                    <a href="#drafts" class="drafts">Drafts <span class="badge">{{$drafts->count()}}</span></a>
                </li>
                <li>
                    <a href="#sent" class="sent">Sent <span class="badge">{{$emails->count()}}</span></a>
                </li>
                <li>
                    <a href="#deleted" class="deleted">Trash Can <span class="badge">{{$trashes->count()}}</span></a>
                </li>                    
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="compose">
                    <img src="{{asset('assets/images/loader.gif')}}" alt="Loading..." />
                </div>
                <div class="tab-pane" id="drafts">
                    @if ($drafts->count() > 0)
                        <button class="drafts-destroy-btn btn btn-danger disabled" style="margin-bottom: 20px" rel="{{URL::to('dashboard/emails/drafts-destroy/1')}}">
                            <span class="glyphicon glyphicon-trash"></span> Destroy
                        </button>
                        <div class='say-something-drafts'></div>
                        <table class="table table-hover">
                            <thead>
                                <th> 
                                    <input type="checkbox" name="checkall-drafts" class="destroy-drafts-checkbox" value="0" id="checkall-drafts2" />
                                </th>     
                                <th>Date Created</th>                               
                                <th>Date Modified</th>
                                <th>Subject</th>
                                <th>Message</th>
                            </thead>              
                            <tbody>
                                @foreach ($drafts as $draft)
                                    <tr>   
                                        <?php 
                                            $mysqldate = new DateTime($draft->created_at);
                                            $nicedate = $mysqldate->format("D jS M Y, H:i");

                                            $mysqldate2 = new DateTime($draft->updated_at);
                                            $nicedate2 = $mysqldate2->format("D jS M Y, H:i");

                                        ?> 
                                        <td>
                                            <label class="checkbox" for="checkbox-drafts-{{$draft->id}}">
                                                <input type="checkbox" name="checkbox-drafts[]" class="destroy-drafts-checkbox" value="{{$draft->id}}" id="checkbox-drafts-{{$draft->id}}" />
                                            </label>
                                        </td>                                             
                                        <td>{{$nicedate}}</td>   
                                        <td>{{$nicedate2}}</td>    
                                        <td>{{$draft->subject}}</td>
                                        <td>
                                            <a class="btn btn-default" data-toggle="modal" href="#view-draft-content{{$draft->id}}">View</a>
                                        </td>                                           
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table> 
                        @foreach ($drafts as $draft)
                            <div class="modal fade" id="view-draft-content{{$draft->id}}">
                                <div class="modal-dialog">
                                    <form action="{{URL::to('dashboard/emails')}}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">{{$draft->subject}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{$draft->message}}
                                                <input type="hidden" name="email-subject" id="email-subject" value='{{$draft->subject}}' />
                                                <input type="hidden" name="email-content" id="email-content" value='{{$draft->message}}' />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary pull-right">
                                                    Send
                                                </button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </form>
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->  
                        @endforeach
                    @else
                        You do not have any items in Drafts
                    @endif
                </div>
                <div class="tab-pane" id="sent">
                    @if ($emails->count() > 0)
                        <button class="delete-btn btn btn-danger disabled" style="margin-bottom: 20px" rel="{{URL::to('dashboard/emails/trash/1')}}">
                            <span class="glyphicon glyphicon-trash"></span> Trash
                        </button>
                        <div class='say-something'></div>
                        <table class="table table-hover">
                            <thead>
                                <th> 
                                    <input type="checkbox" name="checkall1" class="delete-checkbox" value="0" id="checkall1" />
                                </th>                                    
                                <th>Date Sent</th>
                                <th>From</th>
                                <th>Subject</th>
                                <th class="active">Recipients</th>
                                <th class="danger">Bounces</th>
                                <th class="success">Reads</th>
                                <th>Message</th>
                            </thead>              
                            <tbody>
                                @foreach ($emails as $email)
                                    <tr>   
                                        <?php 
                                            $mysqldate = new DateTime($email->created_at);
                                            $nicedate = $mysqldate->format("D jS M Y, H:i");

                                            $bouncers = 0;
                                            $readers = 0;

                                            foreach ($email->trackers as $key => $tracker) 
                                            {
                                                if($tracker->bounced == 1)
                                                    $bouncers += 1;
                                                if($tracker->read == 1)
                                                    $readers += 1;
                                            }
                                        ?> 
                                        <td>
                                            <label class="checkbox" for="checkbox{{$email->id}}">
                                                <input type="checkbox" name="checkbox1[]" class="delete-checkbox" value="{{$email->id}}" id="checkbox{{$email->id}}" />
                                            </label>
                                        </td>                                             
                                        <td>{{$nicedate}}</td>                                       
                                        <td>{{$email->from}}</td> 
                                        <td>{{$email->subject}}</td>
                                        <td class="active">
                                            <a class="btn btn-link" data-toggle="modal" href="#view-recipients{{$email->id}}"><b>{{$email->trackers->count()}}</b></a>
                                        </td>
                                        <td class="danger">
                                            <b>{{$bouncers}}</b>
                                        </td>
                                        <td class="success">
                                            <b>{{$readers}}</b>
                                        </td>
                                        <td>
                                            <a class="btn btn-default" data-toggle="modal" href="#view-email-content{{$email->id}}">View</a>
                                        </td>                                           
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table> 
                        @foreach ($emails as $email)
                            <div class="modal fade" id="view-email-content{{$email->id}}">
                                <div class="modal-dialog">
                                    <form action="{{URL::to('dashboard/emails')}}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">{{$email->subject}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{$email->message}}
                                                <input type="hidden" name="email-subject" id="email-subject" value='{{$email->subject}}' />
                                                <input type="hidden" name="email-content" id="email-content" value='{{$email->message}}' />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary pull-right">
                                                    <span class="glyphicon glyphicon-repeat"></span> Forward Email
                                                </button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </form>
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->  
                            <div class="modal fade" id="view-recipients{{$email->id}}">
                                <div class="modal-dialog">
                                    <form action="{{URL::to('dashboard/emails')}}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">{{$email->subject}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>Recipient</th>
                                                        <th class="danger">Bounced</th>
                                                        <th class="warning">Unread</th>
                                                        <th class="success">Read</th>
                                                    </thead>    
                                                    <tbody>
                                                        @foreach ($email->trackers as $num => $tracker) 
                                                            <tr>
                                                                <td>{{$num + 1}}</td>
                                                                <td>
                                                                    {{$tracker->subscriber->first_name . ' ' . $tracker->subscriber->last_name}} ({{$tracker->subscriber->email}})
                                                                </td>
                                                                <td class="danger">
                                                                    @if($tracker->bounced == 1)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>
                                                                <td class="warning">
                                                                    @if($tracker->read == 0)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>
                                                                <td class="success">
                                                                    @if($tracker->read == 1)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>                                                                    
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                                                                         
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </form>
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->  
                        @endforeach
                    @else
                        You do not have any items in Sent
                    @endif
                </div>
                <div class="tab-pane" id="deleted">
                    @if ($trashes->count() > 0)
                        <button class="destroy-btn btn btn-danger disabled" style="margin-bottom: 20px" rel="{{URL::to('dashboard/emails/destroy/1')}}">
                            <span class="glyphicon glyphicon-trash"></span> Destroy
                        </button>
                        <div class='say-something2'></div>
                        <table class="table table-hover">
                            <thead>
                                <th> 
                                    <input type="checkbox" name="checkall2" class="destroy-checkbox" value="0" id="checkall2" />
                                </th>                                    
                                <th>Date Sent</th>
                                <th>From</th>
                                <th>Subject</th>
                                <th class="active">Recipients</th>
                                <th class="danger">Bounces</th>
                                <th class="success">Reads</th>
                                <th>Message</th>
                            </thead>              
                            <tbody>
                                @foreach ($trashes as $email)
                                    <tr>   
                                        <?php 
                                            $mysqldate = new DateTime($email->created_at);
                                            $nicedate = $mysqldate->format("D jS M Y, H:i");

                                            $bouncers = 0;
                                            $readers = 0;

                                            foreach ($email->trackers as $key => $tracker) 
                                            {
                                                if($tracker->bounced == 1)
                                                    $bouncers += 1;
                                                if($tracker->read == 1)
                                                    $readers += 1;
                                            }
                                        ?> 
                                        <td>
                                            <label class="checkbox" for="checkbox2-{{$email->id}}">
                                                <input type="checkbox" name="checkbox2[]" class="destroy-checkbox" value="{{$email->id}}" id="checkbox{{$email->id}}" />
                                            </label>
                                        </td>                                             
                                        <td>{{$nicedate}}</td>                                       
                                        <td>{{$email->from}}</td> 
                                        <td>{{$email->subject}}</td>
                                        <td class="active">
                                            <a class="btn btn-link" data-toggle="modal" href="#view-recipients{{$email->id}}"><b>{{$email->trackers->count()}}</b></a>
                                        </td>
                                        <td class="danger">
                                            <b>{{$bouncers}}</b>
                                        </td>
                                        <td class="success">
                                            <b>{{$readers}}</b>
                                        </td>
                                        <td>
                                            <a class="btn btn-default" data-toggle="modal" href="#view-email-content{{$email->id}}">View</a>
                                        </td>                                           
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table> 
                        @foreach ($trashes as $email)
                            <div class="modal fade" id="view-email-content{{$email->id}}">
                                <div class="modal-dialog">
                                    <form action="{{URL::to('dashboard/emails')}}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">{{$email->subject}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{$email->message}}
                                                <input type="hidden" name="email-subject" id="email-subject" value="{{$email->subject}}" />
                                                <input type="hidden" name="email-content" id="email-content" value="{{$email->message}}" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                <a class="btn btn-success pull-right" href="{{URL::to('dashboard/emails/move-to-sent'.'/'.$email->id)}}">Move to Sent</a>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </form>
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->  
                            <div class="modal fade" id="view-recipients{{$email->id}}">
                                <div class="modal-dialog">
                                    <form action="{{URL::to('dashboard/emails')}}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">{{$email->subject}}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>Recipient</th>
                                                        <th class="danger">Bounced</th>
                                                        <th class="warning">Unread</th>
                                                        <th class="success">Read</th>
                                                    </thead>    
                                                    <tbody>
                                                        @foreach ($email->trackers as $num => $tracker) 
                                                            <tr>
                                                                <td>{{$num + 1}}</td>
                                                                <td>
                                                                    {{$tracker->subscriber->first_name . ' ' . $tracker->subscriber->last_name}} ({{$tracker->subscriber->email}})
                                                                </td>
                                                                <td class="danger">
                                                                    @if($tracker->bounced == 1)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>
                                                                <td class="warning">
                                                                    @if($tracker->read == 0)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>
                                                                <td class="success">
                                                                    @if($tracker->read == 1)
                                                                        <span class="glyphicon glyphicon-ok"></span>
                                                                    @endif
                                                                </td>                                                                    
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                                                                         
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </form>
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->  
                        @endforeach
                    @else
                        You do not have any items in Trash
                    @endif
                </div>
            </div> <!--tab-content -->
            <div class="hidden content-for-editor">{{$content}}</div>
            <div class="hidden content-for-subject">{{$subject_content}}</div>
     	</div> <!-- COLS -->
    </div> <!--ROW--> 
@stop