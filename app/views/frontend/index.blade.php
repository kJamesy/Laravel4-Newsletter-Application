@extends('frontend._template')
    @if($users > 1)
        <?php $var = $sitename; ?>
    @else
        <?php $var = 'Jl4 v1.1'; ?>
    @endif
@section('title')
	{{$var}}
@stop
@section('page')

    <div class="container-narrow">
        <div class="header">
            <ul class="nav nav-pills pull-right">
                @if($users > 1)
                    <li><a class="login-box" href="{{URL::to('login-form')}}">Admin</a></li>
                @else
                    <li><a class="setup-box" href="{{URL::to('setup-form')}}"><span class="glyphicon glyphicon-wrench"></span> SETUP</a></li>
                    <li style="display:none"><a class="login-box2" href="{{URL::to('login-form')}}">Admin</a></li>
                @endif
            </ul>
            <h1 class="no-margins"><a href="{{URL::to('/')}}">{{$var}}</a></h1>
        </div>
        <div class="row newsletter">
            <div class="jumbotrond" style="text-align: center">
                    <h1>Welcome to {{$var}}</h1>
                    <p>A <i>nifty</i> little newsletter application</p>
            </div>     
            <div id="carousel-example-generic" class="carousel slide">
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="{{asset('assets/images/dashboard.jpg')}}" alt="Features">
                        <div class="carousel-caption">
                            Emails Dashboard
                        </div>
                    </div> 
                    <div class="item">
                        <img src="{{asset('assets/images/email-stats.jpg')}}" alt="Welcome">
                        <div class="carousel-caption">
                            Email statistics
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{asset('assets/images/browser-stats.jpg')}}" alt="Welcome">
                        <div class="carousel-caption">
                            Recipients' browsers statistics
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{asset('assets/images/subscriber-page.jpg')}}" alt="Subscribers Page">
                        <div class="carousel-caption">
                            Subscribers page
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{asset('assets/images/credits.jpg')}}" alt="Credits">
                        <div class="carousel-caption">
                            Built on/with
                        </div>
                    </div>                      
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                    <span class="icon-prev"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                    <span class="icon-next"></span>
                </a>
            </div>
        </div>
        <div style="border-bottom: 2px dotted #333; padding: 20px 0;"></div>
        <div class="pull-left" style="padding-top:10px;"><a href="https://github.com/kJamesy/Laravel4-Newsletter-Application" target="_blank">Github</a></div>
    </div> <!-- /container --> 

@stop