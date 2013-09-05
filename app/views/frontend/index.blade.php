@extends('frontend._template')
@section('title')
	Jl4
@stop
@section('page')

    <div class="container-narrow">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                @if($users > 1)
                    <?php $var = $sitename; ?>
                    <li><a class="login-box" href="{{URL::to('login-form')}}">Admin</a></li>
                @else
                    <?php $var = 'Jl4'; ?>
                    <li><a class="setup-box" href="{{URL::to('setup-form')}}"><span class="glyphicon glyphicon-wrench"></span> SETUP</a></li>
                    <li style="display:none"><a class="login-box2" href="{{URL::to('login-form')}}">Admin</a></li>
                @endif
            </ul>
            <h3 class="text-muted">{{$var}}</h3>
        </div>
        <div class="row newsletter">
            <div class="jumbotrond" style="text-align: center">
                    <h1>Welcome to {{$var}}</h1>
                    <p>A <i>nifty</i> little mailing application</p>
            </div>                
        </div>
            <p>
                <div class="pull-left">&copy;{{$var . ' ' . date('Y')}}</div>
                <!-- <div class="pull-right"><a target="_blank" href="http://acw.uk.com"> &lt;3 Jamesy</a></div> -->
            </p>
    </div> <!-- /container --> 

@stop