@extends('layouts.passwords')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12">
            <center><img src="{{asset('/assets/icons/Fast&Fresh LOGO-C_P.png')}}" width="150"  alt=""></center>
            <ul class="list-group">
               <center><li class="list-group-item">{{__("mail.reset_password_done")}}</li></center>
            </ul>
        </div>
    </div>

    <footer>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="#"><img src="{{asset('/assets/icons/google-play-badge.png')}}" width="150" alt=""></a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <a href="#"><img src="{{asset('/assets/icons/App_Store_Badge.png')}}" width="150"  alt=""></a>
            </div>
        </div>
        <p>Ky mesazh i eshte derguar <a href="mailto:{{$user->email}}">{{$user->email}}</a> ne kerkesen tuaj.</p>
        &copy; 2018 <strong>{{ config('app.name', 'Inovacion') }}</strong>
    </footer>
</div>

@endsection